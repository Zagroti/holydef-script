<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use App\Inside\Constants;
use App\User;
use Closure;
use \Firebase\JWT\JWT;

class loginCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     * @throws \App\Exceptions\ApiException
     */
    public function handle($request, Closure $next)
    {
        $input = array_map(function ($input) {
            if (is_array($input)) {
                return array_map(array($this, 'safeTrim'), $input);
            }
            return trim($input);
        }, $request->all());
        if (!$request->header('Authorization'))
            throw new ApiException(
                ApiException::EXCEPTION_BAD_REQUEST_400,
                'Plz check your Authorization'
            );
        $token = JWT::decode($request->header('Authorization'), config("jwt.secret"), array('HS256'));
        $input['agent'] = $token->agent;
        $input['user_id'] = $token->user_id;
        $request->replace($input);
        return $next($request);
    }


    private function safeTrim($input)
    {
        if (is_array($input)) {
            return array_map(array($this, 'safeTrim'), $input);
        }
        return trim($input);
    }
}
