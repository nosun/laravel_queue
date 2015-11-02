<?php namespace App\Notify\Channel;

use App\Jobs\SendWechat;

class WechatChannel implements Channel
{

    public function sendQueue($notify)
    {

        // 还需考虑异常情况;
        $job = Queue::push(new SendWechat($notify));

        $result = array();

        if (!empty($job)) {
            $result = array(
                'job' => $job,
                'code' => 200
            );
        }

        return $result;
    }

    public function send($notify){

    }
}