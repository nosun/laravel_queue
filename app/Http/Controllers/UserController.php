<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Events;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;
use App\Events\UserLoggedIn;
use Event;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        $user = User::find(1); // 我增加了一条测试数据;

        Log::info('user' . $user->name. 'will login');

        // 事件发生,事件的基本信息通过event对象传递给eventHandler;
        $result = Event::fire(new UserLoggedIn($user));
    }

}
