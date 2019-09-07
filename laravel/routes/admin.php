<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/


/**
 *
 * Auth: Admins
 * Login
 * @see App\Http\Controllers\Auth\Admin
 *
 */
Route::group(['namespace' => 'Auth\Admin'], function(){

	// Login
	Route::post( 'admin/login', 'LoginController@login' );
	Route::get( 'admin/login', 'LoginController@showLoginForm' )->name('admin.login');

	// Logout
	Route::post( 'admin/logout', 'LoginController@logout' )->name('admin.logout');

	// Register
	Route::post( 'admin/users/register', 'RegisterController@register' );
	Route::get( 'admin/users/register', 'RegisterController@showRegistrationForm' )->name('admin.users.register');
});


/**
 *
 * Admin Area
 * @see App\Http\Controllers\Admin
 *
 */
Route::group(['middleware' => 'auth:admin'], function(){

	// Dashboard
	Route::get('admin', function () {
		return redirect()->route('campaigns.index');
	})->name('admin.dashboard');

	Route::get('admin/loggedin',function() {
		return 1;
	});


	// Campaigns List
	Route::get('admin/campaigns', 'Campaigns\CampaignController@list')->name('campaigns.index');

	// Edit Campaigns
	Route::get('admin/campaigns/{id}/edit', 'Campaigns\CampaignController@edit')->name('campaigns.edit');

	// Update Campaigns
	Route::put('admin/campaigns/{id}/edit', 'Campaigns\CampaignController@update')->name('campaigns.update');

	// Get Form Submissions
	Route::get('admin/campaigns/{id}/submissions', 'Campaigns\SubmissionController@list')->name('campaigns.submissions.index');

	// Update Submissions
	Route::put('admin/campaigns/{id}/submissions/{sub}', 'Campaigns\SubmissionController@update')->name('campaigns.submissions.update');

	// Users
	Route::resource('admin/users', 'Admin\UserController', [
	    'as' => 'admin'
	]);

	// Private storage
	Route::get('storage/private/{path}',function($path){

		$imginfo = getimagesize(storage_path('app/private/') . $path);
		header("Content-type: {$imginfo['mime']}");
		readfile(storage_path('app/private/') . $path);

	})->where('path', '.*');


	// Preview Link for Campaigns
	Route::get('preview/{id}','Campaigns\CampaignController@preview')->name('campaigns.preview');

	// Media Controllers
	// @see /app/Http/Controllers/Admin/MediaController.php
	Route::post( 'admin/files/upload' , 'Admin\MediaController@upload' )->name('media.upload');
	Route::get( 'admin/files/browse' , 'Admin\MediaController@browse' )->name('media.browse');
	Route::get( 'admin/files/ajax' , 'Admin\MediaController@ajax' )->name('media.ajax');


	// AJAX Data
	Route::any( 'admin/api/v2/{func}', 'Admin\ApiController@api' )->name('ajax.api');

	// Logs
	Route::get('admin/logs', 'Admin\LogViewerController@index')->name('error.logs');

	// Reports
	Route::get('admin/campaigns/pdf-reports', 'Admin\PDFReportsController@index')->name('reports.index');

	Route::get('admin/campaigns/pdf-reports/{filename}.pdf', 'Admin\PDFReportsController@show')->name('reports.pdf');

});


/**
 *
 * Auth: Users
 * @see App\Http\Controllers\Auth\User
 *
 */
Route::group(['namespace' => 'Auth\User'], function(){
	Route::post( 'register', 'RegisterController@register' )->name('user.register');
	Route::post( 'login', 'LoginController@login' )
		->name('user.login');
	Route::post( 'logout', 'LoginController@logout' )->name('user.logout');
});