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

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'goaltype' => 'required'            
        ]);

        $dir = "Request_response_data/".date('Y-m-d');
           
        if (!file_exists( $dir)) {
                mkdir( $dir, 0777, true);
            }

        //The name of the file that we want to create if it doesn't exist.
        $file = "$dir/goalDetail.txt";
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
                         $results =  DB::select('CALL getGoal( ? , ? )',array($uid , $credentials['goaltype']));

                            $contents = json_encode($results);
                            File::append($file, $contents.PHP_EOL);
               
                          if (sizeof($results)> 0) {
                            $responseData = array( );
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'uv' => 'required'            
        ]);

        $dir = "Request_response_data/".date('Y-m-d');
           
        if (!file_exists( $dir)) {
                mkdir( $dir, 0777, true);
            }

        //The name of the file that we want to create if it doesn't exist.
        $file = "$dir/goalDetail.txt";

          if ($validator->fails()) {
            return response()->json([
                                  "error" => true ,
                                  "statusCode" => 3 ,
                                  "message"=> "can't get data",
                                  "tasks"=> []
                                  ] , 202);
        }else{
                 try{

                            $goal = $request->all();
                            $uid =$request['user']->uid;
                            $totalPercentage = 0; 
                            $goalPercnt =  array( );
                            $goaltype = 0;
                            $percentage = array( );
                            $goalStatus = array( );       
                     
//--------------------------------------------------------------------------                  
                           $results =  DB::select('CALL getGoal( ? , ? )',array($uid , 0));

                                $contents = json_encode($results);
                                File::append($file, $contents.PHP_EOL);
                   
                              if (sizeof($results)> 0) {
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

                                        $percentage[$i] = $tmp;
                                   } 
                                 }

                              $results =  DB::select('CALL getGoal( ? , ? )',array($uid , 1));

                                $contents = json_encode($results);
                                File::append($file, $contents.PHP_EOL);
                   
                              if (sizeof($results)> 0) {
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

                                        $goalStatus[$i] = $tmp;
                                   }                       
                                 }
                           

                             if($percentage != null){
                                  for($i = 0; $i<sizeof($percentage) ; $i++){
                                   $totalPercentage = $totalPercentage + $percentage[$i]['percentage'];
                                  }
                               }



                             $goalPercnt ;
                             if($goalStatus != null){
                              $goalPercnt = self::callInsertGoal($goalStatus[0],$goal,$totalPercentage);
                             }
                             else{
                              $goalPercnt =  self::callInsertGoal($goalStatus,$goal,$totalPercentage);
                             }



                            $responseData =  DB::affectingStatement(
                          'call insertGoal( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )',
                          array( $uid ,  $goal["uv"] ,  $goal["plan"] ,  $goal["info"] ,  $goal["parable"]
                            ,  $goal["business"] ,  $goal["question"] ,  $goal["twentyfist"] ,  $goal["copycat"] ,  $goal["dvd"]
                            ,  $goal["financial"] ,  $goal["welcome"] ,  $goal["qnet"] ,  $goal["earning"] ,  $goal["dream"]
                            ,  $goal["week"] ,  $goal["goal"] , $goalPercnt["currentpercentage"] ,  $goalPercnt["totalPercentage"] ));

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

//-----------------------------------------------------
                   }catch(\PDOException   $ex){
                            return  response()->json([
                                                  "error" => true ,
                                                  "statusCode" => 3 ,
                                                  "message"=> "can't get data exeception",
                                                  "tasks"=> []
                                                  ] , 203);
                        }     
                        // return response()->json(['task'=>  $$results->id],200);
             }         
    }



public function callInsertGoal($goalStatus,$goal,$totalPercentage){

    $currentpercentage=0;
  if($goalStatus!=null && $goal['goal'] == 0){
         $uvratio=0;
         $planratio=0;
         $inforatio=0;
         $knowledgeratio=0;
          
         $parableratio =0;
         $businessratio =0;
         $questionratio =0;
         $twentyfirstratio =0;
         $copycatratio =0;
          
         $totalbookratio=0;
         $dvdratio=0;
         $financialratio=0;
         $welcomeratio=0;
         $qnetprofileratio=0;
          
          
          
          
         $goalcount=0;
         $knowledgcount=0;
         $bookcount=0;
         $bookratio = 0;
          
          if($goalStatus['uv'] !=0){
              $uvratio = $goal["uv"]/$goalStatus['uv'];
              $goalcount++;
          }
          if($goalStatus['plan'] !=0){
              $planratio= $goal["plan"]/$goalStatus['plan'];
              $goalcount++;
          }
          if($goalStatus['info'] !=0){
              $inforatio= $goal["info"]/$goalStatus['info'];
              $goalcount++;
          }

          if($goalStatus['parable'] !=0){
              $parableratio= (1/$goalStatus['parable'])*$goal["parable"];
              $bookcount++;
          }
          if($goalStatus['business'] !=0){
              $businessratio= (1/$goalStatus['business'])*$goal["business"];
              $bookcount++;
          }
          if($goalStatus['question'] !=0){
              $questionratio= (1/$goalStatus['question'])*$goal["question"];
              $bookcount++;
          }
          if($goalStatus['twentyfist'] !=0){
              $twentyfirstratio= (1/$goalStatus['twentyfist'])*$goal["twentyfist"];
              $bookcount++;
          }
          if($goalStatus['copycat'] !=0){
              $copycatratio= (1/$goalStatus['copycat'])*$goal["copycat"];
              $bookcount++;
          }



          if($goalStatus['dvd'] !=0){
              $dvdratio=$goal["dvd"]/$goalStatus['dvd'];
              $knowledgcount++;
          }
          if($goalStatus['financial'] !=0){
              $financialratio=$goal["financial"]/$goalStatus['financial'];
              $knowledgcount++;
          }
          if($goalStatus['welcome'] !=0){
              $welcomeratio=$goal["welcome"]/$goalStatus['welcome'];
              $knowledgcount++;
          
          }
          if($goalStatus['qnet'] !=0){
              $qnetprofileratio=$goal["qnet"]/$goalStatus['qnet'];
              $knowledgcount++;
          }
          
          if($bookcount !=0){
              $totalbookratio = ($parableratio+$businessratio+$questionratio+$twentyfirstratio+$copycatratio)/$bookcount;
              $knowledgcount++;
          }
          
          if($knowledgcount !=0){
              $knowledgeratio= ($totalbookratio+$dvdratio+$financialratio+$welcomeratio+$qnetprofileratio)/$knowledgcount; 
          }
          
          $currentpercentage = $uvratio*10+$planratio*30+$inforatio*40+$knowledgeratio*20;
          
          if(($goal["week"]+0) == $goalStatus['week']&& $goal["goal"] == 0){
              $totalPercentage = $totalPercentage + $currentpercentage;
          
          }

    }
    
    if($goal["goal"] == 1){
        $totalPercentage = 0;
    }

    //console.log($currentpercentage,totalPercentage);
    return [
     "currentpercentage" => $currentpercentage,
      "totalPercentage"=> $totalPercentage
      ];
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
