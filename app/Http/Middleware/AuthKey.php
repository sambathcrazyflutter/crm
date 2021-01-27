<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


use Closure;
use Illuminate\Support\Facades\Auth;
use App\Model\User;
use Validator;


use DB;
Use Exception;
Use App\Exceptions\Handler;
class AuthKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next , ...$roles)
    {
       
       try {
        //Access token from the request        
        $token = JWTAuth::parseToken();
        //Try authenticating user       
       $request['user']  = $token->authenticate();
    } catch (TokenExpiredException $e) {
        //Thrown if token has expired        
        return $this->unauthorized('Your login has expired. Please, login again.',5,403);   
    } catch (TokenInvalidException $e) {
        //Thrown if token invalid
        return $this->unauthorized('User not exist . Please, login again.',4,401);
    }catch (JWTException $e) {
        //Thrown if token was not found in the request.
        return $this->unauthorized('autherisation not provided in url',7,400);
    }

   return $next($request);


    //If user was authenticated successfully and user is in one of the acceptable roles, send to next request.
    // if ($user && in_array($user->role, $roles)) {
    //     return $next($request);
    // }
   // return $this->unauthorized();
}

private function unauthorized($message = null , $statusCode = 6 , $responseCode = 400){
    return response()->json([ 
        'auth'=>  false,
        'message'=> $message ? $message : 'You are unauthorized to access this resource',
        'statusCode'=> $statusCode
     ], $responseCode);

    }
}
