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

class AllcontactsController extends Controller
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
        $file = "$dir/allContactsDetail.txt";
         try{
                 $uid = $request['user']->uid;    
                 $results =  DB::select('CALL allContacts( ? )',array($uid));

                    $contents = json_encode($results);
                    File::append($file, $contents.PHP_EOL);
       
                  if (sizeof($results)> 0) {
                    $responseData = array( );
                      for ($i=0; $i<sizeof($results); $i++)  
                       {  
                          $tmp = array( );
 
                          $tmp["id"] =  $results[$i]->id;
                          $tmp["name"] =  $results[$i]->name;
                          $tmp["initial"] =  $results[$i]->initial;
                          $tmp["phone"] =  $results[$i]->phone;                           
                  
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

    try{  
       
         $uid = $request['user']->uid;    
         $credentials = $request->all();

             if($credentials !=null && sizeof($credentials)>0){
             

                     

                         for($i=0;$i<sizeof($credentials)-1;$i++)
                         {   
                            $object = $credentials[$i];


                            if($object != null){


                                try{

                                     $responseData =  DB::affectingStatement('CALL syncContacts( ? , ? , ? , ? , ? )',array( $uid , $object['idUser'] , $object['name'] , $object['initial'] , $object['phone']
                                      ));

                                     if($responseData==0){
                                         Log::error("not inserted");
                                     }

                                    }catch(\PDOException   $ex){
                                              
                                           Log::error($ex);
                                        } 

                                Log::debug($object);

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
    public function destroy($delete,$contact)
    {
        $val = [
           "delete"=>$delete,
           "contact"=>$contact      
        ];

          $validator = Validator::make($val, [
            'delete' => 'required',
            'contact' => 'required'
        ]);

         if ($validator->fails()) {
            return response()->json([
                                  "error" => true ,
                                  "statusCode" => 3 ,
                                  "message"=> "can't  delete data",
                                  "tasks"=> []
                                  ] , 203);
        }else{

             try{  
               
                // $uid = $request['user']->uid;    
                 $credentials = $validator->validated();
                    
                     $responseData =  DB::affectingStatement('CALL delete_contacts( ? , ? )',array( $credentials['contact'], $credentials['delete']));


                     if($responseData >0){

                               return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=> $credentials['delete'] == 1 ? "contact  deleted" : "contact  recoverd",       
                                   "tasks"=>[]
                               ],201);
                           }else{
                            return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 2,
                                   "message"=> $credentials['delete'] == 1 ? "contact already deleted" : "contact already recoverd" ,
                                   "tasks"=> []
                               ],203);
                           }



                  }catch(\PDOException   $ex){
                        return  response()->json([
                                  "error" => true ,
                                  "statusCode" => 3 ,
                                  "message"=> "can't  delete data",
                                  "tasks"=> []
                                  ] , 203);
                    }            

           }

    }

     /**
     * get deleted contacts  from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getDeletedList(Request $request)
      {
       
        $dir = "Request_response_data/".date('Y-m-d');
           
        if (!file_exists( $dir)) {
                mkdir( $dir, 0777, true);
            }

        //The name of the file that we want to create if it doesn't exist.
        $file = "$dir/allContactsDetail.txt";
         try{
                 $uid = $request['user']->uid;    
                 $results =  DB::select('CALL getDeleted_Contacts( ? )',array($uid));

                    $contents = json_encode($results);
                    File::append($file, $contents.PHP_EOL);
       
                  if (sizeof($results)> 0) {
                    $responseData = array( );
                      for ($i=0; $i<sizeof($results); $i++)  
                       {  
                          $tmp = array( );
 
                          $tmp["id"] =  $results[$i]->id;
                          $tmp["name"] =  $results[$i]->name;
                          $tmp["initial"] =  $results[$i]->initial;
                          $tmp["phone"] =  $results[$i]->phone;                           
                  
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
