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
	Route::get('test',['middleware'=>'throttle:3,1','uses'=>function(){
		return 'ok';
	}]);

	Route::get('help', 'MainController@help');

	//users block
	Route::get('/', 'MainController@index');
	Route::post('/', 'MainController@postIndex');
	Route::get('changeaddress', 'MainController@changeAddress');
	Route::get('open', 'MainController@open');
	Route::get('test', 'MainController@test');
	Route::get('street{id}', 'MainController@getBuilding');
	Route::get('building{id}', 'MainController@getApartment');
	Route::get('feedbacks', 'MainController@getFeedbacks');

	Route::post('save', 'MainController@save');
	Route::post('savefeedback', 'MainController@saveFeedback');

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

    Route::get('admin/changepassword', 'AdminController@getChangepassword');
    Route::post('admin/changepassword', 'AdminController@postChangepassword');

    Route::get('admin/feedbacks', 'AdminController@getFeedbacks');
    Route::get('admin/feedbacks/{id}', 'AdminController@getFeedbacksRead');
    Route::post('admin/feedbacks/save', 'AdminController@postFeedbacksSave');
    Route::get('admin/feedbacks/delete/{id}', 'AdminController@getFeedbackDelAnswer');

    Route::get('admin/database', 'AdminController@getDatabase');
    Route::get('admin/database/trashed', 'AdminController@getDatabaseTrashed');
    Route::get('admin/database/add', 'AdminController@getDatabaseAdd');
    Route::post('admin/database/add', 'AdminController@postDatabaseAdd');
    Route::get('admin/database/delete/{id}', 'AdminController@getDelete');
    Route::get('admin/database/activate/{id}', 'AdminController@getActivate');
    Route::get('admin/database/look', 'AdminController@getLook');
    Route::get('admin/database/look/{ls}', 'AdminController@getLookDetail');

    Route::get('admin/database/restore/{id}', 'AdminController@getRestore');

    Route::get('admin/database/download', 'AdminController@getDownloadCsv');

    Route::get('admin/logs', 'AdminController@getLogs');
    Route::get('admin/logs/read/{file}', 'AdminController@getLogsRead');
    Route::get('admin/logs/delete/{file}', 'AdminController@getLogsDelete');

});