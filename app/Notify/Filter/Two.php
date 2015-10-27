<?php namespace App\Notify\Filter;


class Two implements RuleInterface {

    public function apply($user_channel){
        return $user_channel;
    }
}