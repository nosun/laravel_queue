<?php namespace App\Handlers;

use App\Event;
use App\Notify;
use App\Channel;

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

    public function getNotifyInfo($event_id){
        $channel = array();
        $data = Notify::where('event_id', '=', $event_id)->where('status', '=', 1)->get();
        if(!empty($data)){
            foreach($data as $row){
                $channel_name = self::getChannel($row['channel_id']);
                $channel[]= array(
                    'channel'      => $channel_name,
                    'channel_id'   => $row['channel_id'],
                    'template_id'  => $row['template_id'],
                );
            }
        }
        return $channel;
    }

    public static function getChannel($channel_id){
        $channel = Channel::where('id', '=', $channel_id)->firstOrFail();
        return $channel['name'];
    }

}