<?php namespace App\Handlers\Events;

use App\Events\UserLoggedIn;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Queue;
use App\Commands\SendEmail;
use App\Job;
use App\Notify;
use App\Event;
use App\Handlers\NotifyHelper;

class UserEventHandler
{
    /**
     * Create the event listener.
     *
     * @return void
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
        $helper  = new NotifyHelper($event);
        // 获取event_info
        $info    = $helper->getEventInfo();
        // 获取通道信息
        $notifys = $helper->getNotifyInfo($info['id']);

//        if($config['channel_mail']==true){
//            $data['template'] = $config['template_mail'];
//            // 通过 event_name 获取相关用户,应该做成通用的类
//            //$data['sendTo']   = $this->getSendTo($event_name,$channel);
//            $res = Queue::push(new SendMail($data));
//            if($res){
//                 Job::create([
//                    'job'=>$res,
//                    'type'=>'mail',
//                    'level'=>$data['level'],
//                    'data'=>serialize($data),
//                    'status'=>1,
//                ]);
//            }
//        }
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
    
    private function getEventConfig(){
        // 后面可以增加cache,并可以做成公共的方法，只需要传入name即可;
        $config = EventConfig::where('name', '=', 'userLogin')->firstOrFail();
        return $config;
    }
    
    
}
