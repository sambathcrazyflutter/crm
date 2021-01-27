<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;
use DB;
Use Exception;
Use App\Exceptions\Handler;

use Illuminate\Support\Facades\Auth;
use App\Model\User;
use Validator;

class One108Controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){     

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'feeling' => 'required',
            'knowledge' => 'required',
            'activity' => 'required',
            'nextdayplan' => 'required',
            'goal' => 'required',
            'nextdream' => 'required'
        ]);

         if ($validator->fails()) {
            return response()->json([
                                  "error" => true ,
                                  "statusCode" => 3 ,
                                  "message"=> "can't get data",
                                  "tasks"=> []
                                  ] , 203);
        }else{

            try{
                     $uid = $request['user']->uid;    
                     $credentials = $validator->validated();
                     $responseData =  DB::affectingStatement('CALL insertonezeroeight( ? , ? , ? , ? , ? , ? , ? )',array( $uid , $credentials['feeling'] , $credentials['knowledge'] , $credentials['activity'] , $credentials['nextdayplan'] , $credentials['goal'] , $credentials['nextdream'] ));


                     if($responseData >0){

                               return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=> "data Inserted" ,
                                   "tasks"=>[]
                               ],201);
                           }else{
                            return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 2,
                                   "message"=> " data not inserted" ,
                                   "tasks"=> []
                               ],304);
                           }

                   }catch(\PDOException   $ex){
                        return  response()->json([
                                              "error" => true ,
                                              "statusCode" => 3 ,
                                              "message"=> "can't get data",
                                              "apikey"=> []
                                              ] , 203);
                    }

        }   

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
