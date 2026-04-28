<?php

namespace App\Providers;

use App\Events\OrderPaid;
use App\Listeners\SendOrderConfirmationEmail;
use App\Models\Order;
use App\Policies\OrderPolicy;
use Carbon\CarbonImmutable;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;
use Laravel\Socialite\Two\GithubProvider;
use Laravel\Socialite\Two\GoogleProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        // SSL fix voor Socialite (GitHub, Google, etc.) op localhost
        if (app()->environment('local')) {
            $socialite = $this->app->make(SocialiteFactory::class);

            $socialite->extend('github', function ($app) use ($socialite) {
                $config = $app['config']['services.github'];

                return $socialite->buildProvider(GithubProvider::class, $config)
                    ->setHttpClient(new Client(['verify' => false]));
            });

            $socialite->extend('google', function ($app) use ($socialite) {
                $config = $app['config']['services.google'];

                return $socialite->buildProvider(GoogleProvider::class, $config)
                    ->setHttpClient(new Client(['verify' => false]));
            });
        }

        // Register event-driven listeners
        Event::listen(
            OrderPaid::class,
            SendOrderConfirmationEmail::class,
        );
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
