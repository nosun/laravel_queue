<?php namespace App\Handlers\Pusher;


interface  Pusher {

    public function push($notify);
}