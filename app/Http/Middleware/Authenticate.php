<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader != 'application/json') {
            return response()->json(["message" => "Bad request"], 400);
        }
        if(!Auth::check()) {
            return response()->json(["message" => 'Authentication fails'], 401);
        }
        return $next($request);
    }
}