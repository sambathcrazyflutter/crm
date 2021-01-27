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

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'name' => 'required',
            'address' => 'required',
            'job' => 'required',
            'sallery' => 'required',
            'family' => 'required',
            'country' => 'required',
            'state' => 'required',
            'place' => 'required',
            'dtime' => 'required'
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
                     $responseData =  DB::affectingStatement('CALL insertProfileDetails( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )',array( $credentials['cid'] , $credentials['name'] , $credentials['address'] , $credentials['job']  , $credentials['sallery']  , $credentials['family'] , $credentials['country'] , $credentials['state']   , $credentials['place'] , $credentials['dtime'] ));


                      if( $responseData >0 ){

                               return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=> "Profile Inserted" ,
                                   "tasks"=>[]
                               ],201);
                           }else{
                            return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 2,
                                   "message"=> " Profile not inserted" ,
                                   "tasks"=> []
                               ],203);
                           }

                   }catch(\PDOException   $ex){
                        return  response()->json([
                                              "error" => true ,
                                              "statusCode" => 3 ,
                                              "message"=> "Profile already exist",
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
    public function show($profile)
     {
            $dir = "Request_response_data/".date('Y-m-d');
               
            if (!file_exists( $dir)) {
                    mkdir( $dir, 0777, true);
                }

            //The name of the file that we want to create if it doesn't exist.
            $file = "$dir/detailsDetail.txt";
             try{
                     $results =  DB::select('CALL getProfileById( ? )',array($profile));

                        $contents = json_encode($results);
                        File::append($file, $contents.PHP_EOL);
           
                      if (sizeof($results)> 0) {
                        $responseData = array( );
                          for ($i=0; $i<sizeof($results); $i++)  
                           {  
                              $tmp = array( );  
                             
                              $tmp["id"] =  $profile;
                              $tmp["name"] =  $results[$i]->name;
                              $tmp["address"] =  $results[$i]->address;
                              $tmp["job"] =  $results[$i]->job;  
                              $tmp["sallery"] =  $results[$i]->sallery;
                              $tmp["family"] = $results[$i]->family;
                              $tmp["country"] =  $results[$i]->country;
                              $tmp["state"] =  $results[$i]->state;  
                              $tmp["dtime"] =  $results[$i]->jobtime;
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
                            
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $profile)
   {
      
           $validator = Validator::make($request->all(), [
            'cid' => 'required',
            'name' => 'required',
            'address' => 'required',
            'job' => 'required',
            'sallery' => 'required',
            'family' => 'required',
            'country' => 'required',
            'state' => 'required',
            'place' => 'required',
            'dtime' => 'required'
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
                     $responseData =  DB::affectingStatement('CALL updateProfileDetails( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )',array( $credentials['cid'] , $credentials['name'] , $credentials['address'] , $credentials['job']  , $credentials['sallery']  , $credentials['family'] , $credentials['country'] , $credentials['state']   , $credentials['place'] , $credentials['dtime'] ));


                      if( $responseData >0 ){

                               return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 1,
                                   "message"=> "Profile Updated" ,
                                   "tasks"=>[]
                               ],200);
                           }else{
                            return  response()->json([
                                   "error"=> false,
                                   "statusCode" => 2,
                                   "message"=> " Profile not Updated" ,
                                   "tasks"=> []
                               ],203);
                           }

                   }catch(\PDOException   $ex){
                        return  response()->json([
                                              "error" => true ,
                                              "statusCode" => 3 ,
                                              "message"=> "Profile can't update",
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
