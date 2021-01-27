<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// Route::get('/', 'AllcontactsController@index')->name('allcontacts.index');

// Route::post('/', 'AllcontactsController@store')->name('allcontacts.store');

// Route::delete('/{delete}/{contact}', 'AllcontactsController@destroy')->name('allcontacts.destroy');

// Route::get('/delete', 'AllcontactsController@getDeletedList')->name('allcontacts.getDeletedList');


Route::get('/all', 'EightbasicsController@all')->name('eightbasics.all');


Route::apiResource('/eightbasics','EightbasicsController',[
  'only' => ['index', 'store', 'update', 'destroy']
]);

Route::apiResource('/relationship','RelationshipController',[
   'only' => ['index', 'store', 'update']
]);

Route::apiResource('/details','DetailsController',[
  'only' => ['index', 'store', 'update']
]);

Route::apiResource('/profile','ProfileController',[
  'only' => [ 'show', 'store', 'update']
]);




// Route::any('{url?}/{sub_url?}/{sub_url2?}', function(){

//      return  response()->json([
//                               "error" => true ,
//                               "statusCode" => 3 ,
//                               "message"=> "route is not supported",
//                               "task"=> []
//                               ] , 405);
                    
// });
