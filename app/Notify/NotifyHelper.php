<?php namespace App\Notify;

use App\Event;
use App\Notify;
use App\Channel;
use App\Notify\Filter\UserSettingFilter;

class NotifyHelper {

    public $event_name;
    public $notify_channel;
    public $notify_template;

    public function __construct($event){
        $this->getEventName($event);
    }

    private function getEventName($event){
        $event_class = get_class($event);
        $arr = explode('\\',$event_class);
        $this->event_name = end($arr);
    }

    public function getEventInfo(){
        $data = Event::where('name','=',$this->event_name)->firstOrFail();
        $info = array(
            'id' => $data['id'],
            'name' => $data['name'],
            'level' => $data['level']
        );
        return $info;
    }

    public function getChannel($event_id){
        $channel = array();
        $data = Notify::where('event_id', '=', $event_id)->where('status', '=', 1)->get();
        if(!empty($data)){
            foreach($data as $row){
                $channel_name = self::getChannelName($row['channel_id']);
                $channel[]= array(
                    'channel'      => $channel_name,
                    'channel_id'   => $row['channel_id'],
                    'template_id'  => $row['template_id'],
                );
            }
        }
        return $channel;
    }

    public function getUsersChannels($user_ids,$event_id){
        $notifies = array();


        return $notifies;
    }

    // 根据user_id，event_id，user_setting 获取user_event_channels
    public function getUserChannels($user_id,$event_id){

        $user_channels = array();
        $channels = $this->getChannel($event_id);

        if(!empty($channels)){
            foreach($channels as $row){
                $user_channels[$row['channel_id']] = $row['channel'];
            }
        }

        $filter = new UserSettingFilter($user_id);
        $user_channels = $filter->apply($user_channels);
        return $user_channels;
    }

    // 使用 Redis存储时 有用。当用户修改设置时，批量的设置用户在各个事件上的Channel
    public function setUserChannels($user_id,$event_id){


    }

    // 使用 Redis存储时 有用。当增加事件channel，或者修改事件的channel时，批量的修改UserChannel
    public function setChannelUsers(){


    }


    public function getNotify($channels,$user_id){


    }

    public static function getChannelName($channel_id){
        $channel = Channel::where('id', '=', $channel_id)->firstOrFail();
        return $channel['name'];
    }

}