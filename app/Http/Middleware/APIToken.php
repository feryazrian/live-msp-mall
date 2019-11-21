<?php

namespace Marketplace\Http\Middleware;

use Closure;
use Marketplace\AuthAccessToken;

class APIToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth = $request->bearerToken();

        if(!$auth){
            return $this->denied(401, 'Unauthorized');
        }

        $userCheck = AuthAccessToken::jwtValidate($auth);
        if ($userCheck->status != 200) {
            return $this->denied($userCheck->status, $userCheck->message);
        }

        return $next($request);
    }

    public function denied($code, $message)
    {
        return response()->api($code, $message);
    }
}
