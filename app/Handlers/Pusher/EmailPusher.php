<?php namespace App\Handlers\Pusher;

use App\Commands\SendEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Queue;

class EmailPusher implements Pusher {

    public function push($notify){

        // 还需考虑异常情况;
        $job = Queue::push(new SendEmail($notify));

        $result = array();

        if(!empty($job)){
            $result = array(
                'job'  => $job,
                'code' => 200
            );
        }

        return $result;
    }
}