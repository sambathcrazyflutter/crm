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

use Illuminate\Support\Facades\Log;

class RelationshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
     {

        
 
                    $dir = "Request_response_data/".date('Y-m-d');
                       
                    if (!file_exists( $dir)) {
                            mkdir( $dir, 0777, true);
                        }

                    //The name of the file that we want to create if it doesn't exist.
                    $file = "$dir/relationDetail.txt";
                     try{
                             $uid = $request['user']->uid;    
                             $results =  DB::select('CALL getRelationShipList( ? )',array($uid));

                                $contents = json_encode($results);
                                File::append($file, $contents.PHP_EOL);
                   
                              if (sizeof($results)> 0) {
                                $responseData = array( );
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
                                           "tasks"=> [] 
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
          try{  
       
                 $uid = $request['user']->uid;    
                 $credentials = $request->all();

                     if($credentials !=null && sizeof($credentials)>0){
                     
                             for($i=0;$i<sizeof($credentials)-1;$i++)
                             {   
                                $phone = $credentials[$i];


                                if($phone != null){


                                    try{

                                         $responseData =  DB::affectingStatement('CALL insertRelationShip( ? , ? )',array( $uid ,
                                          $phone   ));

                                         if($responseData==0){
                                             Log::error("not inserted");
                                         }

                                        }catch(\PDOException   $ex){
                                                  
                                               Log::error($ex);
                                            } 

                                    Log::debug($phone);

                                }else{
                                     Log::debug([
                                            "contact"=> 'Empty',
                                            "exp"=> '-----------------------------------------------'
                                              ]);
                                }
                             }   

                                

                            return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=> "data synced" ,
                                   "tasks"=>[] 
                               ],201);

                       }else{
                        return  response()->json([
                               "error"=> false,
                               "statusCode" => 2,
                               "message"=> " data not synced" ,
                               "tasks"=> []
                           ],304);
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
    public function update(Request $request, $relationship)
    {

             try{  
               
                   
                     $responseData =  DB::affectingStatement('CALL update_relation( ? )',array( $relationship ));


                     if($responseData >0){

                               return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=>  "contact  updated",       
                                   "tasks"=>[]
                               ],201);
                           }else{
                            return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 2,
                                   "message"=>  "contact already updated" ,
                                   "tasks"=> []
                               ],203);
                           }



                  }catch(\PDOException   $ex){
                        return  response()->json([
                                  "error" => true ,
                                  "statusCode" => 3 ,
                                  "message"=> "can't  update data",
                                  "tasks"=> []
                                  ] , 203);
                    }            

           

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
