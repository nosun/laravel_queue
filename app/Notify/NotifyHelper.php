<?php namespace App\Notify;

use App\Event;
use App\Notify;
use App\Channel;
use App\NotifyLog;
use App\NotifyTemplate;
use App\User;
use App\Notify\Filter\UserSettingFilter;
use App\Notify\Channel\ChannelFactory;
use Config;
use Exception;

class NotifyHelper {

    public $event_info;

    public function __construct($event){
        $event_name = $this->getEventName($event);
        $this->event_info = $this->getEventInfo($event_name);
        if(empty($this->event_info)){
            throw new Exception("Undefined Event", 1);
        }
    }

    // 初始化，得到事件的名称
    private function getEventName($event){
        $event_class = get_class($event);
        $arr = explode('\\',$event_class);
        return end($arr);
    }

    // 初始化，得到事件的信息
    private function getEventInfo($event_name){
        $data = Event::where('name','=',$event_name)->firstOrFail();
        $info = array(
            'id' => $data['id'],
            'name' => $data['name'],
            'type' => $data['type'],
            'level' => $data['level']
        );
        return $info;
    }

    // 发送通知
    public function Notify($user_ids,$message){
        $notifies   = $this->getUsersChannels($user_ids,$this->event_info['id']);

        if(empty($notifies)){                                         // 没有需要发送消息的通道
            return;
        }

        $user_model = new User();
        $users = $user_model->getUsers($user_ids);                    // user_ids 总

        foreach($notifies as $channel_id => $user_ids){

            $receiver = $this->getContract($users,$user_ids);         // 需要发送的对象 user_ids 分
            $template = $this->getTemplate($this->event_info['id'],$channel_id);
            if(empty($template)){
                throw new \Exception('The template Not found',2);     // 异常需要系统的收集一下;
                continue;
            }

            $notify = array(
                'level'    => $this->event_info['level'],
                'receiver' => $receiver,
                'template' => $template,
                'message'  => $message
            );

            $channel = ChannelFactory::createChannel($channel_id);
            $result = $channel->sendQueue($notify); // 要求pusher 返回 array('job','code');

            $log = array(
                'event_id'   => $this->event_info['id'],
                'type'       => $this->event_info['type'],
                'channel_id' => $channel_id,
                'level'   => $this->event_info['level'],
                'job'     => isset($result['job'])?$result['job']:'',
                'payload' => serialize($message),
                'status'  => isset($result['code'])?$result['code']:0
            );
            // var_dump($log);die;

            NotifyLog::create($log);
        }
    }

    // 获得实际 channel-> user 所有通道的所有用户
    public function getUsersChannels($user_ids,$event_id){
        $users_channels = array();
        $channel_ids = $this->getChannel($event_id);

        // 反复考虑，此处最终输出 user_id => array $user_channels 这样的数组;
        if(!empty($channel_ids) && !empty($user_ids)){
            foreach($user_ids as $user_id){
                $users_channels[$user_id] = $channel_ids;
            }
        }

        $filter = new UserSettingFilter();
        $users_channels = $filter->apply($users_channels);

        // 转换成 channels => user_ids
        $channels_users = $this->transpose($users_channels);
        return $channels_users;
    }


    // 获取基于事件的默认通道 channel_ids
    public function getChannel($event_id){
        $channel_ids = array();
        $data = Notify::where('event_id', '=', $event_id)->where('status', '=', 1)->get();
        if(!empty($data)){
            foreach($data as $row){
                $channel_ids[]= $row['channel_id'];
            }
        }
        return $channel_ids;
    }


    // 基于event_id,channel_id 获取template_path
    public function getTemplate($event_id,$channel_id){
        $notify   = Notify::where('event_id', '=', $event_id)
            ->where('channel_id',$channel_id)->where('status', '=', 1)->firstOrFail();
        if(!empty($notify)){
            $result   = NotifyTemplate::findOrFail($notify['template_id']);
            $template = $result->path;
            return $template;
        }
        return false;
    }


    // 将user_ids => channel_ids 转换为 channel_ids => user_ids
    private function transpose($array){
        $arr = array();
        foreach($array as $key => $_arr){
            foreach($_arr as $value){
                $arr[$value][] = $key;
            }
        }
        ksort($arr);
        return $arr;
    }

    // 获取channel_name 暂时无用
    public static function getChannelName($channel_id){
        $channel = Channel::where('id', '=', $channel_id)->firstOrFail();
        return $channel['name'];
    }


    // 考虑到 发送信息的时候，可以选择具体的 方式，比如 email,phone,以及可能还需要使用用户信息，因此这里选择发送完整的用户信息。
    public function getContract($users,$user_ids){
        $receiver = array();
        foreach($users as $row){
            if(in_array($row->id,$user_ids)){
                $receiver[] = $row;
            }
        }
        return $receiver;
    }


//    // 获取通道下 用户的 contacts
//    public function getContract($channel_id,$user_ids){
//        $contracts = array();
//
//        if(!empty($channel_id) && is_array($user_ids) && !empty($user_ids)){
//            foreach($user_ids as $user_id){
//                $contract = $this->getUserChannelInfo($channel_id,$user_id);
//
//                if($contract){
//                    $contracts[] = $contract;
//                }
//            }
//        }
//
//        return $contracts;
//    }


//    // 由配置文件读取 channel_id 和 用户表字段的对应关系
//    public function getUserChannelInfo($channel_id,$user_id){
//        $keys = Config::get('notify.channel_user');
//        $user = User::find($user_id);
//        if($user){
//            return $user[$keys[$channel_id]];
//        }
//    }

}