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

 // Route::get('/', 'WelcomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
// 	'password' => 'Auth\PasswordController',
]);

Route::group(['prefix' => 'backend'], function () {
	Route::get('/', 'Backend\IndexController@index');
	Route::controllers([
		'wood-piece' => 'Backend\WoodPieceController',
		'fire-wood' => 'Backend\FireWoodController',
		'profit-loss' => 'Backend\ProfitLossController',
		'performance' => 'Backend\PerformanceController',
		'user' => 'Backend\UserController',
	]);
});

Route::group(['prefix' => 'api'], function () {
	Route::controllers([
		'user' => 'Api\UserApiController',
		'service' => 'Api\ServiceApiController',
		'transaction' => 'Api\TransactionApiController',
		'dialyreport' => 'Api\DialyReportApiController',
		'profit' => 'Api\ProfitApiController',
		'performance' => 'Api\PerformanceApiController',
		'utility' => 'Api\UtilityApiController',
		'document' => 'DocumentController'
	]);
});
