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

class DetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
      public function index(Request $request)
    {

          $validator = Validator::make($request->all(), [
            'cid' => 'required'
              ]);

         if ($validator->fails()) {
            return response()->json([
                                  "error" => true ,
                                  "statusCode" => 3 ,
                                  "message"=> "can't  get data",
                                  "tasks"=> []
                                  ] , 203);
        }else{
 
                    $credentials = $validator->validated();
                
                    $dir = "Request_response_data/".date('Y-m-d');
                       
                    if (!file_exists( $dir)) {
                            mkdir( $dir, 0777, true);
                        }

                    //The name of the file that we want to create if it doesn't exist.
                    $file = "$dir/detailsDetail.txt";
                     try{
                             $uid = $request['user']->uid;    
                             $results =  DB::select('CALL getDetailsList( ? )',array($credentials['cid']));

                                $contents = json_encode($results);
                                File::append($file, $contents.PHP_EOL);
                   
                              if (sizeof($results)> 0) {
                                $responseData = array( );
                                  for ($i=0; $i<sizeof($results); $i++)  
                                   {  
                                      $tmp = array( );                                     
                                      $tmp["id"] =  $results[$i]->id;
                                      $tmp["name"] =  $results[$i]->name;
                                      $tmp["msg"] =  $results[$i]->msg;
                                      $tmp["problem"] =  $results[$i]->problem;  
                                      $tmp["nextplan"] =  $results[$i]->nextPlan;
                                      $tmp["place"] = $results[$i]->place;                            
                                      $tmp["time"] = date('y-m-d H:i:s', strtotime($results[$i]->created_on));
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
            'msg' => 'required',
            'problem' => 'required',
            'nextplan' => 'required',
            'place' => 'required'
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
                     $responseData =  DB::affectingStatement('CALL insertDetails( ? , ? , ? , ? , ?  )',array( $credentials['cid'] , $credentials['msg'] , $credentials['problem'] , $credentials['nextplan']  , $credentials['place']));


                      if( $responseData >0 ){

                               return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=> "Details Inserted" ,
                                   "tasks"=>[]
                               ],201);
                           }else{
                            return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 2,
                                   "message"=> " Details not inserted" ,
                                   "tasks"=> []
                               ],203);
                           }

                   }catch(\PDOException   $ex){
                        return  response()->json([
                                              "error" => true ,
                                              "statusCode" => 3 ,
                                              "message"=> "can't inserte data",
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $detail)
    {
       

        $validator = Validator::make($request->all(), [
            'cid' => 'required',
            'msg' => 'required',
            'problem' => 'required',
            'nextplan' => 'required',
            'place' => 'required'
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
                     $responseData =  DB::affectingStatement('CALL updateDetails( ? , ? , ? , ? , ?  )',array( $detail , $credentials['msg'] , $credentials['problem'] , $credentials['nextplan']  , $credentials['place']));


                      if( $responseData >0 ){

                               return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=> "Details updated" ,
                                   "tasks"=>[]
                               ],201);
                           }else{
                            return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 2,
                                   "message"=> " Details not updated" ,
                                   "tasks"=> []
                               ],203);
                           }

                   }catch(\PDOException   $ex){
                        return  response()->json([
                                              "error" => true ,
                                              "statusCode" => 3 ,
                                              "message"=> "can't updated data",
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
    public function destroy($id)
    {
        //
    }
}
