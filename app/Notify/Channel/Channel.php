<?php namespace App\Notify\Channel;


interface  Channel {

    public function sendQueue($notify);
    public function send($notify);
}