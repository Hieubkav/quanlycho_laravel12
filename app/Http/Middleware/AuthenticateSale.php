<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class AuthenticateSale extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        return parent::handle($request, $next, 'sale');
    }

    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('khaosat.login');
    }
}
