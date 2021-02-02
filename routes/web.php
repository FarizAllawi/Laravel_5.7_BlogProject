<?php

use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;
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

Route::prefix('app')->middleware(AdminCheck::class)->group(function(){
    // Admin Login 
    Route::post('/adminLogin', 'AdminController@adminLogin');

    // Tags Routes
    Route::post('/getTags','AdminController@getTags');
    Route::post('/addTag','AdminController@addTag');
    Route::post('/editTag','AdminController@editTag');
    Route::post('/deleteTag','AdminController@deleteTag');

    // Category Routes
    Route::post('/upload','AdminController@upload');
    Route::post('/deleteUpload','AdminController@deleteUpload');
    Route::post('/getCategory' , 'AdminController@getCategory');
    Route::post('/addCategory' , 'AdminController@addCategory');
    Route::post('/editCategory' , 'AdminController@editCategory');
    Route::post('/deleteCategory' , 'AdminController@deleteCategory');

    // Admin-users Routes
    Route::post('/getUsers' , 'AdminController@getUsers');
    Route::post('/addUsers' , 'AdminController@addUsers');
    Route::post('/editUsers' , 'AdminController@editUsers');
    Route::post('/deleteUsers' , 'AdminController@deleteUsers');

    // Role User Management
    Route::post('/getRoles', 'AdminController@getRoles');
    Route::post('/addRoles', 'AdminController@addRoles');
    Route::post('/editRole', 'AdminController@editRole');
    Route::post('/deleteRole', 'AdminController@deleteRole');

});

Route::get('/logout', 'AdminController@logout');
Route::get('/', 'AdminController@index');
Route::any('/{slug}' , 'AdminController@index');










