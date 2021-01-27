<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Model\User;
use Validator;


use DB;
Use Exception;
Use App\Exceptions\Handler;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
  
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
 try{
        $credentials = $validator->validated();
        $response =  DB::select('call validateLogin(?,?)',array($credentials['email'] , $credentials['password']));
       
        if($response != null){
           // return response()->json($response[0] , 200);

            if( $response[0]->allowed == 1 ){ 

              if (! $token = auth()->tokenById($response[0]->id)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }else{

                    return  response()->json([
                                  "error" => false ,
                                  "statusCode" => 1 ,
                                  "message"=> "Loged In" ,
                                  "name" => $response[0]->username,
                                  "apikey"=> "Bearer $token" ] , 200);

                }

            }

             if( $response[0]->allowed == 0 ){ 
                 return  response()->json([
                                  "error" => true ,
                                  "statusCode" => 2 ,
                                  "message"=> "User Not Activated",
                                  "apikey"=> ""
                                  ] ,203);
              }
               if( $response[0]->allowed == 2 ){ 
                 return  response()->json([
                                  "error" => true ,
                                  "statusCode" => 4 ,
                                  "message"=> "Login Failed - Email Id err!",
                                  "apikey"=> ""
                                  ] , 203);
              }
               if( $response[0]->allowed == 3 ){ 
                return  response()->json([
                                  "error" => true ,
                                  "statusCode" => 5 ,
                                  "message"=> "Login Failed - Password err!",
                                  "apikey"=> ""
                                  ] , 203);
              }



            }
         }catch(\PDOException   $ex){
            return  response()->json([
                                  "error" => true ,
                                  "statusCode" => 3 ,
                                  "message"=> "can't get data",
                                  "apikey"=> ""
                                  ] , 203);
        }    

     }



    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:user',
            'password' => 'required|string|min:5',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = $validator->validated();
try{
        $response = DB::select('call create_user( ? , ? , ? )',array($user['name'] , $user['email'] , $user['password']));
       
        if($response >= 1){
             return response()->json([
                  "error"=> false,
                  "statusCode" => 1,
                  "message"=> "Account Created"
                ], 201);

        }else{
                return response()->json([
                  "error"=> false,
                  "statusCode" => 2,
                  "message"=> "Account not Created"
                ], 304);
            }


        }catch(\PDOException   $ex){
            return  response()->json([
                                  "error" => true ,
                                  "statusCode" => 3 ,
                                  "message"=> "User Alredy Exist!",
                                  "apikey"=> ""
                                  ] , 203);
        }  



    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => "bearer $token",
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

}