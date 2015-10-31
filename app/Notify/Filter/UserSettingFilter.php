<?php namespace App\Notify\Filter;

use Illuminate\Config;
use App\NotifyRule;
use App\UserNotifySetting;

class UserSettingFilter {

    protected $disableChannels;
    protected $enableChannels;

    public function __construct(){

    }

    // user_ids, users
    public function apply($users_channels){
        $factory = new RuleFactory();
        array_walk($users_channels,function(&$user_channels,$user_id) use ($factory) {
            // 获取用户设置
            $user_rules = $this->getRules($user_id);
            // 过滤
            foreach($user_rules as $rule){
                $filter = $factory->getRule($rule->name);
                $user_channels = $filter->apply($user_channels,$user_rules);
            }
        });
        return $users_channels;
    }

    public function getRules($user_id){
        $UserNotifySetting = new UserNotifySetting();
        $rules = $UserNotifySetting->getRulesByUserId($user_id);
        return $rules;
    }

}