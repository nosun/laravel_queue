<?php namespace App\Notify\Filter;


class One implements RuleInterface {

    public function apply($user_channel){
        return $user_channel;
    }
}