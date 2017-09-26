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

Route::get('/threads', 'Threads@index')->name('threads.index');
Route::get('/threads/create', 'Threads@create')->name('threads.create');
Route::delete('/threads/{thread}', 'Threads@destroy')->name('threads.destroy');

Route::post('/threads/{thread}/subscribe', 'ThreadsSubscriptions@subscribe')->name('threads.subscribe');
Route::delete('/threads/{thread}/subscribe', 'ThreadsSubscriptions@unSubscribe')->name('threads.unsubscribe');

Route::get('/threads/{channel}/{thread}', 'Threads@show')->name('channel.threads.show');
Route::post('/threads', 'Threads@store')->name('threads.store');
Route::get('/threads/{channel}', 'Threads@index')->name('threads.by_channel');

Route::get('/threads/{channel}/{thread}/replies', 'Replies@index')->name('replies.index');
Route::post('/threads/{channel}/{thread}/replies', 'Replies@store')->name('replies.store');

Route::patch('/replies/{reply}', 'Replies@update')->name('replies.update');
Route::delete('/replies/{reply}', 'Replies@destroy')->name('replies.destroy');

Route::post('/replies/{reply}/favorites', 'Favorites@store')->name('reply.favorite');
Route::delete('/replies/{reply}/favorites', 'Favorites@destroy')->name('reply.unfavorite');

Route::get('/profiles/{profileUser}', 'Profiles@show')->name('profiles.show');

Route::get('/profiles/{user}/notifications', 'UserNotifications@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotifications@destroy');

Route::get('/users', 'Users@index')->name('users.index');
