<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        try {

            $user = JWTAuth::parseToken()->authenticate();

        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                throw new AuthenticationException('Token is Invalid');
            } else if ($e instanceof TokenExpiredException) {
                throw new AuthenticationException('Token is Expired');
            } else {
                throw new AuthenticationException('Authorization Token not found');
            }
        }

        if (! in_array($user->type, explode('|',$guard))) {
            throw new AuthenticationException('Token is Invalid!');
        }

        return $next($request);
    }
}
