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



Route::group(['prefix' => 'client/view'], function ()
{
    Route::get('room', function()
    {
        return view('client.room');
    });

    Route::get('slide', function()
    {
        return view('client.slide');
    });

    Route::get('directional', function()
    {
        return view('client.directional');
    });

    // Rodney test to display all events in rooms
    Route::get('allrooms', function()
    {
        return view('client.allrooms');
    });
});

Route::group(['prefix' => 'client/data'], function ()
{
    Route::get('room', 'ClientEventsController@room');
    // Rodney test to display all events in rooms
    // Route::get('allrooms', 'ClientEventsController@allrooms');
    Route::get('allrooms/{IP}', 'ClientEventsController@allrooms');
    Route::get('directional', 'ClientEventsController@directional');
});

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

Route::group(['middleware' => ['web']], function ()
{
    Route::get('/', 'PageController@home');
    Route::post('login', 'PageController@loginUser');
    Route::get('logout', 'PageController@logout');
    Route::get('overview', 'PageController@overview');
    Route::get('room/{id}', 'EventController@room');
    Route::get('event/{id}/edit', 'EventController@editEvent');
    Route::get('event/create/{roomID}/{time}', 'EventController@createEvent');
    Route::get('event/delete/{id}/{room}', 'EventController@delete');
    Route::post('event/update', 'EventController@updateEvent');
    Route::post('event/save', 'EventController@saveEvent');
    Route::get('events', 'EventController@allEvents');
});
