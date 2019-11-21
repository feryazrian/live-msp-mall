<?php

namespace Marketplace\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Marketplace\Authorization;

class ApiAuthorization
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
        if (empty($request->api_key))
        {
            return $this->denied();
        }
        if (empty($request->api_secret))
        {
            return $this->denied();
        }
        
        $apiKey = $request->api_key;
        $apiSecret = $request->api_secret;

        $auth = Authorization::where('api_key', $apiKey)
            ->where('api_secret', $apiSecret)
            ->first();

        if (empty($auth))
        { 
            return $this->denied();
        }
        
        return $next($request);
    }

    public function denied()
    {
        $responses = array(
            'status_code' => 401,
            'status_message' => 'Unauthorized',
            'items' => null,
        );
        return response()->json($responses, $responses['status_code']);
    }
}