<?php namespace App\Notify\Filter;


class Two implements RuleInterface {

    // 不发送微信 2
    public function apply($user_channel,$user_rules){
        $key = array_search(2,$user_channel); // 这里的 1 代表的是 channel_id 1，也可以考虑从数据中取出来;
        unset($user_channel[$key]);
        return $user_channel;
    }
}