<?php

namespace App\Http\Middleware;

use Closure;

class AuthKey
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
        $token = $request->header('Authorization');

        if($token == 'A'){
            return response()->json(['message'=>'APP key  Not Found','api'=>$request->header()],401);
        }
        return $next($request);
    }
}
