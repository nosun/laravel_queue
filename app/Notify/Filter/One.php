<?php namespace App\Notify\Filter;


class One implements RuleInterface {

    // 不发送邮件 1, user_rules 有时候也是过滤判断的条件，因此传入，但不一定用;
    public function apply($user_channel,$user_rules){
        $key = array_search(1,$user_channel); // 这里的 1 代表的是 channel_id 1，也可以考虑从数据中取出来;
        unset($user_channel[$key]);
        return $user_channel;
    }
}