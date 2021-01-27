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

class EightbasicsController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

          $validator = Validator::make($request->all(), [
            'type' => 'required'
              ]);

         if ($validator->fails()) {
            return response()->json([
                                  "error" => true ,
                                  "statusCode" => 3 ,
                                  "message"=> "can't  delete data",
                                  "tasks"=> []
                                  ] , 203);
        }else{
 
                    $credentials = $validator->validated();
                
                    $dir = "Request_response_data/".date('Y-m-d');
                       
                    if (!file_exists( $dir)) {
                            mkdir( $dir, 0777, true);
                        }

                    //The name of the file that we want to create if it doesn't exist.
                    $file = "$dir/t8BasicsDetail.txt";
                     try{
                             $uid = $request['user']->uid;    
                             $results =  DB::select('CALL get8basicLists( ? , ? )',array($uid,$credentials['type']));

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
                                      $tmp["time"] = date('y-m-d H:i:s', strtotime($results[$i]->updated_on));
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
          }      // return response()->json(['task'=>  $$results->id],200);
                        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'cid' => 'required',
            'basic' => 'required',
            'percentage' => 'required'
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
                     $responseData =  DB::select('CALL insert8Basic( ? , ? , ? )',array( $credentials['cid'] , $credentials['basic'] , $credentials['percentage'] ));


                      if( $responseData[0]->inserted == 1 ){

                               return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=> "Contact Inserted" ,
                                   "tasks"=>[]
                               ],201);
                           }else{
                            return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 2,
                                   "message"=> " Contact already inserted" ,
                                   "tasks"=> []
                               ],203);
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
    public function update(Request $request, $eightbasic){

        $validator = Validator::make($request->all(), [
            'cid' => 'required',
            'basic' => 'required',
            'percentage' => 'required'
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
                     $responseData =  DB::affectingStatement('CALL update8Basic( ? , ? , ? )',array( $credentials['cid'] , $credentials['basic'] , $credentials['percentage'] ));


                      if( $responseData>0 ){

                               return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=> "Contact updated" ,
                                   "tasks"=>[]
                               ],200);
                           }else{
                            return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 2,
                                   "message"=> " Contact already updated" ,
                                   "tasks"=> []
                               ],203);
                           }

                   }catch(\PDOException   $ex){
                        return  response()->json([
                                              "error" => true ,
                                              "statusCode" => 3 ,
                                              "message"=> "can't update contact",
                                              "apikey"=> []
                                              ] , 203);
                    }

        }   

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($eightbasic)
    {
         try{
                     $responseData =  DB::affectingStatement('CALL delete8Basic( ? )',array( $eightbasic ));


                      if( $responseData>0 ){

                               return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=> "Contact deleted" ,
                                   "tasks"=>[]
                               ],200);
                           }else{
                            return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 2,
                                   "message"=> " Contact already deleted" ,
                                   "tasks"=> []
                               ],203);
                           }

                   }catch(\PDOException   $ex){
                        return  response()->json([
                                              "error" => true ,
                                              "statusCode" => 3 ,
                                              "message"=> "can't delete contact",
                                              "apikey"=> []
                                              ] , 203);
                    }
    }


 public function all(Request $request)
    {

        

            $dir = "Request_response_data/".date('Y-m-d');
               
            if (!file_exists( $dir)) {
                    mkdir( $dir, 0777, true);
                }

            //The name of the file that we want to create if it doesn't exist.
            $file = "$dir/t8BasicsDetail.txt";
             try{
                     $uid = $request['user']->uid;    
                     $results =  DB::select('CALL get8basicContacts( ? )',array($uid));

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
                              $tmp["time"] = date('y-m-d H:i:s', strtotime($results[$i]->updated_on));
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
       // return response()->json(['task'=>  $$results->id],200);
                        
    }




}
