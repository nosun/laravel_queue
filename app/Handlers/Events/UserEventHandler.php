<?php namespace App\Handlers\Events;

use App\Events\UserLoggedIn;
use App\Event;
use App\Handlers\NotifyHelper;
use App\Handlers\Pusher\PusherFactory;
use App\NotifyLog;
use Exception;

class UserEventHandler
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    private $user;

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
        $this->user = $event->user;
        $helper     = new NotifyHelper($event);
        $info       = $helper->getEventInfo();              // 获取event_info
        $eventMsg   = $this->getEventMsg();
        $notifies   = $helper->getNotifyInfo($info['id']); // 获取通道信息

        if(empty($notifies)){
            return;                                       // 没有需要发送消息的通道
        }

        foreach($notifies as $row){

            $receiver = $this->getEventReceiver($row['channel']);

            if(!empty($receiver)){
                $notify = array(
                    'level'       => $info['level'],
                    'receiver'    => $receiver,
                    'template_id' => $row['template_id'],
                    'message'     => $eventMsg
                );

                $pusher = PusherFactory::createPusher($row['channel']);
                $result = $pusher->push($notify); // 要求pusher 返回 array('job','code');
            }else{
                $result['code'] = 404;
            }

            $log = array(
                'event_id'   => $info['id'],
                'channel_id' => $row['channel_id'],
                'level'   => $info['level'],
                'job'     => isset($result['job'])?$result['job']:'',
                'payload' => serialize($eventMsg),
                'status'  => isset($result['code'])?$result['code']:0
            );

            NotifyLog::create($log);
        }
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

    public function getEventReceiver($channel){

        switch($channel){
            case 'sms':
                return $this->user['phone'];
            case 'email':
                return $this->user['email'];
            case 'wechat':
                return $this->user['wechat'];
            case 'siteMsg':
                return $this->user['id'];
            case 'default':
                throw new Exception('no this channel');
        }

    }

    public function getEventMsg(){

        return 'userLogin';

    }
    
}
