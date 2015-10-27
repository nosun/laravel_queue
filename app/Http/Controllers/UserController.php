<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Contracts\Events;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;
use App\Events\UserLoggedIn;
use Event;
use App\User;

use App\Notify\Filter\UserSettingFilter;

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

        $data = array();
        $data['time']  = time();
        $data['event'] = 'login';
        $data['user']  = $user;

        // 事件发生,事件的基本信息通过event对象传递给eventHandler;
        $result = Event::fire(new UserLoggedIn($data));
    }

    public function test(){

        $array = array(
            array(
                'user_id' => 1,
                'user_channel' => array(
                    'email','wechat','sms'
                )
            ),
            array(
                'user_id' => 2,
                'user_channel' => array(
                    'email','wechat','sms'
                )
            ),
        );
        $filter = new UserSettingFilter();

        $result = $filter->apply($array);



    }

}
