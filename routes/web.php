<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/new', 'TestController@controllerMethod');
Route::any('{slug}', function(){
    return view('welcome');
});


// Tags Routes
Route::post('app/getTags','AdminController@getTags');
Route::post('app/addTag','AdminController@addTag');
Route::post('app/editTag','AdminController@editTag');

// Category Routes
Route::post('app/deleteTag','AdminController@deleteTag');
Route::post('app/upload','AdminController@upload');
Route::post('app/deleteUpload','AdminController@deleteUpload');
Route::post('app/getCategory' , 'AdminController@getCategory');
Route::post('app/addCategory' , 'AdminController@addCategory');
Route::post('app/editCategory' , 'AdminController@editCategory');






