<?php

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

Route::get('/', function () {
    return view('welcome');
});




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/scan',function(){


	return view('scan');
});


Route::get('/threads','ThreadsController@index')->name('threads');


//Endpoint to search threads 

Route::get('/threads/search','SearchController@show');

Route::get('/threads/create','ThreadsController@create');

Route::get('/threads/{channel}','ThreadsController@index');


//show A Thread 
Route::get('/threads/{channel}/{thread}','ThreadsController@show');
//Delete a thread 
Route::delete('/threads/{channel}/{thread}','ThreadsController@destroy');

//Update Thread 
Route::patch('/threads/{channel}/{thread}','ThreadsController@update');

Route::post('/threads','ThreadsController@store')->name('threads');


//Lock a thread 

Route::post('/locked-threads/{thread}','LockedThreadsController@store')
	->name('locked-threads.store')
	->middleware('admin');

Route::delete('/locked-threads/{thread}','LockedThreadsController@destroy')
	->name('locked-threads.destroy')
	->middleware('admin');



Route::get('/threads/{channel}/{thread}/replies','RepliesController@index');

Route::post('/threads/{channel}/{thread}/replies','RepliesController@store');

Route::post('/threads/{channel}/{thread}/subscriptions','ThreadSubscriptionsController@store')->middleware('auth');

Route::delete('/threads/{channel}/{thread}/subscriptions','ThreadSubscriptionsController@destroy')->middleware('auth');

//endpoint to delete replies 

Route::delete('/replies/{reply}','RepliesController@destroy')
	->name('replies.destroy');

Route::patch('/replies/{reply}','RepliesController@update');


Route::post('/replies/{reply}/favorites','FavoritesController@store');

Route::delete('/replies/{reply}/favorites','FavoritesController@destroy');


//endpoint to mark reply as best Reply 

Route::post('/replies/{reply}/best','BestRepliesController@store')
	->name('best-reply.store');


Route::get('/profiles/{user}','ProfilesController@show')->name('profile');

Route::get('/profiles/{user}/notifications','UserNotificationsController@index');

Route::delete('/profiles/{user}/notifications/{notification}','UserNotificationsController@destroy');

Route::get('/api/users','Api\UsersController@index');



Route::post('/api/users/{user}/avatar','Api\UserAvatarController@store')->middleware('auth')->name('avatar');





