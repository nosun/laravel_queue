<?php namespace App\Notify\Filter;


class Four implements RuleInterface {

    public function apply($user_channel){
        return $user_channel;
    }
}