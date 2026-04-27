<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * Senior Reflex: We check for auth first, then use the Enum to verify admin status.
         */
        if (! $request->user() || ! $request->user()->role instanceof UserRole || ! $request->user()->role->isAdmin()) {
            abort(403, 'Toegang geweigerd. U heeft geen beheerdersrechten.');
        }

        return $next($request);
    }
}
