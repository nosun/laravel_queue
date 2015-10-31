<?php namespace App\Notify\Filter;


class Three implements RuleInterface {

    public function apply($user_channel,$user_rules){
        return $user_channel;
    }
}