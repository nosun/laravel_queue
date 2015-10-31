<?php namespace App\Notify\Filter;


class Four implements RuleInterface {

    public function apply($user_channel,$user_rules){
        return $user_channel;
    }
}