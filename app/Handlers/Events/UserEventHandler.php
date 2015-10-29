<?php namespace App\Handlers\Events;

use App\Events\UserLoggedIn;
use App\Event;
use App\Notify\Channel\Channel;
use App\Notify\NotifyHelper;
use App\Notify\Channel\ChannelFactory;
use App\NotifyLog;
use Exception;

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
        $this->data = $event->data;
        $helper     = new NotifyHelper($event);
        $info       = $helper->getEventInfo();              // 获取event_info
        $channels   = $helper->getChannel($info['id']);  // 获取默认情况下事件的通知方式

        // 此处根据事件返回用户id即可，如果有通用的方式，就独立出来，否则就放在事件的处理方法中。
        $user_ids =  $this->data['user']->id; // 这里只是例子而已，实际过程中每个事件取法可能会不同

        if(empty($channels) || empty($user_ids)){
            return;
        }

        $notifies = $helper->getUserChannels($user_ids,$info['id']);
        var_dump($notifies);die;

        if(empty($notifies)){                                         // 没有需要发送消息的通道
            return;
        }

        foreach($notifies as $row){

            //$receiver = $this->getEventReceiver($row['channel']);

            if(!empty($receiver)){
                $notify = array(
                    'level'       => $info['level'],
                    'receiver'    => $receiver,
                    'template_id' => $row['template_id'],
                    'message'     => $eventMsg
                );

                $channel = ChannelFactory::createChannel($row['channel']);
                $result = $channel->sendQueue($notify); // 要求pusher 返回 array('job','code');
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

}
