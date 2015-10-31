<?php namespace App\Notify\Channel;

use App\Commands\SendEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Queue;

class EmailChannel implements Channel {

    public function sendQueue($notify){

        $job = Queue::push(new SendEmail($notify));

        if(!empty($job)){
            $result = array(
                'job'  => $job,
                'code' => 200
            );
        }else{
            $result = array(
                'job'  => '',
                'code' => 404
            );
        }

        return $result;
    }

    public function send($notify){

        $result = new sendEmail($notify);

        return $result;
    }
}