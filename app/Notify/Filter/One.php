<?php namespace App\Notify\Filter;


class One implements RuleInterface {

    // 不发送邮件 1
    public function apply($user_channel){
        unset($user_channel[1]);
        return $user_channel;
    }
}