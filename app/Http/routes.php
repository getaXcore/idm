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

/* Route::get('/', function () {
    return view('welcome');
}); */

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


//WSDL SOAP SERVICE
Route::get('soapi/v2/server','SoapServer\NewServer@server');
Route::group(['prefix' => 'soapi/v2','middleware' => ['basicAuth']],function (){
    Route::post('server','SoapServer\NewServer@server');
});

/**
 * Utilities of Delete Payment Installment Where flag=0
 */
Route::get('util/delete','Utilities\util@deleteOfInstallment');
