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

use Illuminate\Contracts\Events;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;

use App\Commands\SendEmail;
use App\Handlers\Events\UserEventHandler;
use App\Events\UserLoggedIn;

Route::get('/test',function(){
    
    $user = 'xiaoming';
    Log::info($user. 'will login');
    $result = Event::fire(new UserLoggedIn($user));
    
    dd($result);
    Log::info($user.'Logined');

});

Route::get('/', function () {
    return view('welcome');
});