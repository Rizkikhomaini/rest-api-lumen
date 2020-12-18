<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\User;
use App\Helper;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->header(env('HEADER'));
        
        if(!$token) {
            // Unauthorized response if token not there
            return Helper::response([
                'error' => 'Token not provided.'
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(ExpiredException $e) {
            return Helper::response([
                'error' => 'Provided token is expired.'
            ], 400);
        } catch(Exception $e) {
            return Helper::response([
                'error' => 'An error while decoding token.'
            ], 400);
        }

        $user = User::find($credentials->sub);

        if ($user->suspended == "1") {
            return Helper::response([
                'error' => 'Account suspended.'
            ], 400);
        }

        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;

        return $next($request);
    }
}