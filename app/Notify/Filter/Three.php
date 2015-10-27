<?php namespace App\Notify\Filter;


class Three implements RuleInterface {

    public function apply($user_channel){
        return $user_channel;
    }
}