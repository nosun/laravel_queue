<?php namespace App\Handlers\Events;

use App\Events\UserLoggedIn;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Queue;
use App\Commands\SendEmail;

class UserEventHandler
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserLoggedIn  $event
     * @return void
     */
    public function onUserLogin(UserLoggedIn $event)
    {
        $msg = 'user ' .$event->user .'logined';
        Queue::push(new SendEmail($msg));
    }
}
