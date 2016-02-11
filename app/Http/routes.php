<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
	//users block
	Route::get('/', 'MainController@index');
	Route::get('not-available', function(){
		return view('not-available');
	});
	
	//administrator block
    Route::get('admin/login', ['middleware'=>'guest', 'uses'=>'AdminController@getLogin']);
	Route::post('admin/login', 'AdminController@postLogin');
	Route::get('admin/logout', 'AdminController@getLogout');
});


Route::group(['middleware' => ['admin']], function () {
	
    Route::get('admin', 'AdminController@getAdmin');
    Route::get('admin/settings', 'AdminController@getSettings');
    Route::post('admin/settings', 'AdminController@postSettings');


});