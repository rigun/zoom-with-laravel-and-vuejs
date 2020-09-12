<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.

// Route::get('/testpdf','Internship\MainController@testPDF');
Route::group(['middleware' => ['jwt.verify']], function() {

    Route::group(['namespace' => 'Master'], function () {
        Route::group(['middleware' => ['role.verify:superadministrator']], function(){
            Route::get('/role','RoleController@getData');
            Route::get('/role/all','RoleController@listQuery');
            Route::post('/role','RoleController@store');
            Route::post('/role/{id}','RoleController@update');
            Route::delete('/role/{id}','RoleController@destroy');
        });
    });
    Route::group(['namespace' => 'User'], function () {
        Route::group(['middleware' => ['role.verify:superadministrator']], function(){
            Route::get('/manage-admin','AdministratorController@getData');
            Route::post('/manage-admin','AdministratorController@store');
            Route::post('/manage-admin/{id}','AdministratorController@update');
            Route::delete('/manage-admin/{id}','AdministratorController@destroy');
        });
        Route::group(['middleware' => ['role.verify:superadministrator|administrator']], function(){
            Route::get('/manage-customer','CustomerController@getData');
            Route::post('/manage-customer','CustomerController@store');
            Route::post('/manage-customer/{id}','CustomerController@update');
            Route::delete('/manage-customer/{id}','CustomerController@destroy');
        });
        Route::get('/user', 'MainController@getAuthenticatedUser');
        Route::post('/user/changepassword', 'MainController@changepassword');
    });

    // Get list of meetings.
    Route::get('/meetings', 'Zoom\MeetingController@list');
    
    Route::get('/manage-meeting/zoomdata', 'Zoom\MeetingController@getZoomData');
    Route::delete('/manage-meeting/zoomdata/{id}', 'Zoom\MeetingController@destroy');
    Route::post('/manage-meeting/meetings', 'Zoom\MeetingController@create');
    // Create meeting room using topic, agenda, start_time.

    // Get information of the meeting room by ID.
    Route::get('/meetings/{id}', 'Zoom\MeetingController@get')->where('id', '[0-9]+');
    Route::patch('/meetings/{id}', 'Zoom\MeetingController@update')->where('id', '[0-9]+');
    Route::delete('/meetings/{id}', 'Zoom\MeetingController@delete')->where('id', '[0-9]+');

});

Route::post('/logout', 'AuthController@logout');
Route::post('/login', 'AuthController@login');
Route::post('/registration', 'AuthController@registration');