<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//Route::get('/', 'WelcomeController@index');
Route::get('/', 'HomeController@index');
Route::post('querys', 'ReporteadorController@querys');
Route::post('llenaGridQuery', 'ReporteadorController@llenaGridQuery');
Route::get('reporteador', 'ReporteadorController@index');
Route::post('generarvista', 'ReporteadorController@generarvista');
Route::post('consultavistas', 'ReporteadorController@consultavistas');
Route::post('generaconexion', 'ReporteadorController@generaconexion');
Route::post('probarconexion', 'ReporteadorController@probarconexion');





Route::post('Login', 'OrdenesController@getOrdenes');


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController'
	
]);

//modelos
Route::get('consultas', function(){



});
