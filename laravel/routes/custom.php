<?php

/*
|--------------------------------------------------------------------------
| Custom Routes
|--------------------------------------------------------------------------
|
| Here is where you can register custom web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::group(['middleware' => 'auth:admin'], function(){

	// Login
	Route::get( 'admin/phpinfo', 'Admin\PhpInfoController@phpinfo' );

});

Route::get('mail/{view}',function($view){
	$args = [
		'to' => [
			'address' => 'example@example.com',
			'name' => 'John Smith',
		],
		'submission' => \Submission::find(1),
		'campaign_id' => 1,
		'status_comment' => \Submission::find(1)->meta('status_comment'),
		'kayo' => 1,
		'kayo_voucher' => 'ABC133XXX666',
		'kayo_link' => '#',
		'request' => (object) [
			'first_name' => 'John',
			'last_name' => 'Smith',
			'email' => 'example@example.com',
			'phone' => '0411222333',
			'status_comment' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
		]
	];
	return view("campaigns.mail.{$view}",$args);
})->middleware('auth:admin');


Route::get('admin/campaigns/winners',function(){
	return view("admin.campaigns.winners",[
		'title'=> 'Campaign Winners',
	]);
})->middleware('auth:admin')->name('campaigns.winners');


Route::get('messages/{view}',function($view){
	return view("campaigns.messages.{$view}",['campaign'=>\Campaign::find(1),'campaign_id'=>1]);
})->middleware('auth:admin');
