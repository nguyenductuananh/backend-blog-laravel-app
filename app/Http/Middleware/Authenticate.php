<?php

namespace App\Http\Middleware;

use App\Enums\HttpStatusCode;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader != 'application/json') {
            return response()->json(response_format_data(__('response-message.bad-request'), HttpStatusCode::BAD_REQUEST), HttpStatusCode::BAD_REQUEST);
        }
        if (!Auth::check()) {
            return response()->json(response_format_data(__("response-message.auth-fail"), HttpStatusCode::FORBIDDEN), HttpStatusCode::FORBIDDEN);
        }
        return $next($request);
    }
}
