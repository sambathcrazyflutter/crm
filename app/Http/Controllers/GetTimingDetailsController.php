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

class GetTimingDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'category' => 'required',
            'from' => 'required',
            'to' => 'required'
        ]);

        $dir = "Request_response_data/".date('Y-m-d');
           
        if (!file_exists( $dir)) {
                mkdir( $dir, 0777, true);
            }

        //The name of the file that we want to create if it doesn't exist.
        $file = "$dir/timingDetail.txt";

         if ($validator->fails()) {
            return response()->json([
                                  "error" => true ,
                                  "statusCode" => 3 ,
                                  "message"=> "can't get data",
                                  "tasks"=> []
                                  ] , 222);
        }else{

               try{
                     $uid = $request['user']->uid; 
                     $credentials = $validator->validated();
                     $results =  DB::select('CALL getTimingDetails( ? , ? , ? , ? )',array($uid , $credentials['category']  , $credentials['from'] , $credentials['to']));
                    
                        $contents = json_encode($results);
                        File::append($file, $contents.PHP_EOL);
           

                      if (sizeof($results)> 0){
                          $responseData = array( );

                          if( $credentials['category'] >0 &&  $credentials['category']  <=6){
                             
                              for ($i=0; $i<sizeof($results); $i++)  
                               {  
                                  $tmp = array( );
                                  
                                  $tmp["id"] =  $results[$i]->id;
                                  $tmp["idUser"] =  $results[$i]->idUser;
                                  $tmp["name"] =  $results[$i]->name;
                                  $tmp["initial"] =  $results[$i]->initial;
                                  $tmp["phone"] =  $results[$i]->phone;  
                                  $tmp["basic"] =  $results[$i]->basic;
                                  $tmp["time"] = date('y-m-d H:i:s', strtotime($results[$i]->updated_on));
                                  $tmp["percentage"] = (string)$results[$i]->percentage;
                                  $responseData[$i] = $tmp;
                               }  

                            }
                            if( $credentials['category'] == 7){

                              for ($i=0; $i<sizeof($results); $i++)  
                               {  
                                  $tmp = array( );
                                  
                                  $tmp["id"] =  $results[$i]->id;
                                  $tmp["idUser"] =  $results[$i]->idUser;
                                  $tmp["name"] =  $results[$i]->name;
                                  $tmp["initial"] =  $results[$i]->initial;
                                  $tmp["phone"] =  $results[$i]->phone;  
                                  $tmp["basic"] =  $results[$i]->basic;
                                  $tmp["time"] = date('y-m-d H:i:s', strtotime($results[$i]->created_on));
                                  $tmp["percentage"] = (string)$results[$i]->percentage;
                                  $responseData[$i] = $tmp;
                               }  
                            }

                           if( $credentials['category'] == 8){

                              for ($i=0; $i<sizeof($results); $i++)  
                               {  
                                    
                                    $tmp = array( );

                                    $tmp["id"] = $results[$i]->id;
                                    $tmp["uv"] = $results[$i]->uv;
                                    $tmp["plan"] = $results[$i]->plan;
                                    $tmp["info"] = $results[$i]->info;
                                    $tmp["parable"] = $results[$i]->parable;
                                    $tmp["business"] = $results[$i]->business;
                                    $tmp["question"] = $results[$i]->question;
                                    $tmp["twentyfist"] = $results[$i]->twentyfist;
                                    $tmp["copycat"] = $results[$i]->copy;
                                    $tmp["dvd"] = $results[$i]->dvd;
                                    $tmp["financial"] = $results[$i]->financial;
                                    $tmp["welcome"] = $results[$i]->welcome;
                                    $tmp["qnet"] = $results[$i]->qnet;
                                    $tmp["earning"] = $results[$i]->earning;
                                    $tmp["dream"] = $results[$i]->dream;
                                    $tmp["week"] = $results[$i]->week;
                                    $tmp["goal"] = $results[$i]->goal;
                                    $tmp["percentage"] = $results[$i]->percentage;
                                    $tmp["time"] = date('y-m-d H:i:s', strtotime($results[$i]->created_on));

                                    $responseData[$i] = $tmp;
                                
                                }
                            }
                            if( $credentials['category'] == 9){

                              for ($i=0; $i<sizeof($results); $i++)  
                               {  
                                    
                                    $tmp = array( );
                                    $tmp["id"] = $results[$i]->id;
                                    $tmp["feeling"] = $results[$i]->feeling;
                                    $tmp["knowledge"] = $results[$i]->knowledge;
                                    $tmp["activity"] = $results[$i]->activity;
                                    $tmp["nextdayplan"] = $results[$i]->nextdayplan; 
                                    $tmp["goal"] = $results[$i]->goal;
                                    $tmp["nextdream"] = $results[$i]->nextdream;
                                    $tmp["time"] = date('y-m-d H:i:s', strtotime($results[$i]->created_on));

                                    $responseData[$i] = $tmp;
                                }    
                            }

                           if(isset($responseData)){

                               return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=> "data exist" ,
                                   "tasks"=>$responseData 
                               ],200);
                            }else{
                                    return  response()->json([
                                           "error"=> false,
                                           "statusCode" => 2,
                                           "message"=> " no data" ,
                                           "tasks"=> $responseData  
                                       ],203);
                                 }
                 
                         } else {
                          return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 2,
                                   "message"=> "user has no data" ,
                                   "tasks"=> [] 
                               ],200);
                         }
                         
                    }catch(\PDOException   $ex){
                            return  response()->json([
                                                  "error" => true ,
                                                  "statusCode" => 3 ,
                                                  "message"=> "can't get data",
                                                  "tasks"=> []
                                                  ] , 203);
                        } 

            }        
                // return response()->json(['task'=>  $$results->id],200);
                        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
