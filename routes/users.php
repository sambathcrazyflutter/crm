<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::apiResource('/calllog','CalllogController',[
  'only' => ['index']]);

Route::apiResource('/goal','GoalController',[
  'only' => ['index', 'store']
]);

Route::apiResource('/one108','One108Controller',[
  'only' => ['store']
]);

Route::apiResource('/getTrackingList','GetTrackingListController',[
  'only' => ['index']
]);

Route::apiResource('/getTimingDetails','GetTimingDetailsController',[
   'only' => ['index']
]);



Route::get('/contacts', 'AllcontactsController@index')->name('allcontacts.index');

Route::post('/contacts', 'AllcontactsController@store')->name('allcontacts.store');

Route::delete('/contacts/{delete}/{contact}', 'AllcontactsController@destroy')->name('allcontacts.destroy');

Route::get('/contacts/delete', 'AllcontactsController@getDeletedList')->name('allcontacts.getDeletedList');





Route::any('{url?}/{sub_url?}/{sub_url2?}', function(){

     return  response()->json([
                              "error" => true ,
                              "statusCode" => 3 ,
                              "message"=> "route is not supported",
                              "task"=> []
                              ] , 405);
                    
});