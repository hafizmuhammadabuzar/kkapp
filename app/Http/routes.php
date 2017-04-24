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

/* Api Routes Start */

Route::group(['prefix' => 'api'], function () {
		Route::get('/', 'ApiController@index');
		Route::post('/signup', 'ApiController@register');
		Route::post('/update-profile', 'ApiController@updateProfile');
		Route::post('/new-corporate', 'ApiController@corporateRegister');
		Route::post('/signin', 'ApiController@login');
		Route::resource('/signout', 'ApiController@logout');
		Route::resource('/forgot-password', 'ApiController@forgotPassword');
		Route::get('/get-countries-cities', 'ApiController@getCountriesCities');
		Route::get('/change-password', 'ApiController@changePassword');
		Route::post('/save-password', 'ApiController@changePassword');
		Route::get('/save-token', 'ApiController@saveToken');
		Route::resource('/save-android-token', 'ApiController@saveAndroidToken');
		Route::post('/save-user-preferred', 'ApiController@saveUserPreferred');
		Route::get('/get-categories', 'ApiController@getCategories');
		Route::post('/add-event', 'ApiController@addEvent');
		Route::resource('/get-events', 'ApiController@getEvents');
		Route::resource('/like-event', 'ApiController@saveFavourite');
		Route::resource('/share-event', 'ApiController@eventShare');
		Route::resource('/get-favourite-events', 'ApiController@getUserFavouriteEvents');
		Route::resource('/get-user-events', 'ApiController@getUserEvents');
	});
Route::get('/user/verification/{slug}', 'ApiController@userVerify');

// Admin Routes
Route::get('admin/login', 'AdminController@index');
Route::post('admin/login', 'AdminController@index');
Route::get('admin/logout', 'AdminController@logout');
Route::group(['middleware' => 'checkLogin', 'prefix' => 'admin'], function () {
		Route::get('/', 'AdminController@index');
		Route::get('/add-event', 'AdminController@eventDetails');

		Route::resource('/add-category', 'AdminController@addCategory');
		Route::get('/view-categories', 'AdminController@viewCategories');
		Route::get('/edit-category/{num}', 'AdminController@updateCategory');
		Route::post('/update-category', 'AdminController@updateCategory');
		Route::get('/delete-category/{id}', 'AdminController@deleteCategory');

		Route::resource('/add-type', 'AdminController@addType');
		Route::get('/view-types', 'AdminController@viewTypes');
		Route::get('/edit-type/{num}', 'AdminController@updateType');
		Route::post('/update-type', 'AdminController@updateType');
		Route::get('/delete-type/{id}', 'AdminController@deleteType');

		Route::get('/add-user', 'AdminController@AddUser');
		Route::post('/save-user', 'AdminController@AddUser');
		Route::get('/view-users', 'AdminController@viewUsers');
		Route::get('/view-verified-users', 'AdminController@viewVerifiedUsers');
		Route::get('/edit-user/{num}', 'AdminController@updateUser');
		Route::get('/edit-verified-user/{num}', 'AdminController@updateUser');
		Route::post('/update-user', 'AdminController@updateUser');
		Route::get('/delete-user/{id}', 'AdminController@deleteUser');
		Route::get('/delete-user/{id}', 'AdminController@deleteUser');
		Route::get('/delete-verified-user/{id}', 'AdminController@deleteVerifiedUser');

		Route::get('/add-event', 'AdminController@addEvent');
		Route::post('/save-event', 'AdminController@addEvent');
		Route::post('/update-event', 'AdminController@addEvent');
		Route::get('/edit-event/{num}', 'AdminController@eventDetails');
		Route::get('/duplicate-event/{num}', 'AdminController@eventDetails');
		Route::resource('/view-events', 'AdminController@viewEvents');
		Route::get('/event-detail/{num}', 'AdminController@eventDetails');
		Route::get('/delete-event/{num}', 'AdminController@deleteEvent');
		Route::post('/search-events', 'AdminController@searchEvents');
		Route::get('/event-status/{num}', 'AdminController@EventStatus');
	});

Route::group(['prefix' => 'user'], function () {
		Route::get('/', 'UserController@index');
		Route::post('/login', 'UserController@index');

		Route::get('/add-event', 'UserController@addEvent');
		Route::post('/save-event', 'UserController@addEvent');
		Route::post('/update-event', 'UserController@addEvent');
		Route::get('/edit-event/{num}', 'UserController@eventDetails');
		Route::get('/duplicate-event/{num}', 'UserController@eventDetails');
		Route::get('/view-events', 'UserController@viewEvents');
		Route::get('/event-detail/{num}', 'UserController@eventDetails');
		Route::get('/delete-event/{num}', 'UserController@deleteEvent');
		Route::get('user', 'UserController@addEvent');
	});
