<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        // 1. Bestaat social account al?
        $account = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($account) {
            Auth::login($account->user);

            return redirect(
                $account->user->isAdmin()
                    ? '/dashboard'
                    : '/products'
            );
        }

        // 2. Zoek user via email
        $user = null;

        if ($socialUser->getEmail()) {
            $user = User::where('email', $socialUser->getEmail())->first();
        }

        // 3. Maak user indien nodig
        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
            ]);
        }

        // 4. Koppel social account
        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar(),
        ]);

        Auth::login($user);

        return redirect(
            $user->isAdmin()
                ? '/dashboard'
                : '/products'
        );
    }
}
