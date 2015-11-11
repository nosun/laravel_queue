<?php namespace App\Channel;

use App\Jobs\SendEmail;
use Illuminate\Foundation\Bus\DispatchesJobs;

class EmailChannel implements Channel {

    use DispatchesJobs;
    public function sendQueue($notify){

        $job = (new SendEmail($notify))->onQueue('email')->delay(1);
        $res = $this->dispatch($job);

        if(!empty($res)){
            $result = array(
                'job'  => $res,
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