<?php namespace App\Handlers\Events;

use App\Events\UserLoggedIn;
use App\Event;
use App\Notify\NotifyHelper;

class UserEventHandler
{

    /**
     * $data array
     *
     */
    private $data;

    /**
     * Create the event listener.
     *
     */

    public function __construct()
    {


    }

    /**
     * Handle the event.
     *
     * @param  UserLoggedIn  $event
     * @return void
     */
    public function onUserLogin(UserLoggedIn $event)
    {
        $notifyHelper = new NotifyHelper($event);

        // 根据事件返回用户id，如果有通用的方式，就独立出来，否则就放在事件的处理方法中。
        $user_ids =  array($event->data['user']->id,2); // 这里只是例子而已，实际过程中每个事件取法可能会不同

        // event data 可以在这里进行处理
        $message = array(
            'subject' => 'user login',
            'content' => 'you are welcome',
            'url'     => 'http://www.xzx.com',
            'user_id' => $event->data['user']->id
        );

        $notifyHelper->Notify($user_ids,$message);
    }

    public function  onUserLogout(UserLoggedOut $event)
    {
        //
    }
    
    public function subscribe($events){
        
         $events->listen(
            'App\Events\UserLoggedIn',
            'App\Handlers\Events\UserEventHandler@onUserLogin'
        );

        $events->listen(
            'App\Events\UserLoggedOut',
            'App\Handlers\Events\UserEventHandler@onUserLogout'
        );
    }

}
