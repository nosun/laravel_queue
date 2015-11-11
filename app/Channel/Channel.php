<?php namespace App\Channel;


interface  Channel {

    public function sendQueue($notify);
    public function send($notify);
}