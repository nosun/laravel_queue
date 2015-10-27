<?php namespace App\Notify\Filter;

use Illuminate\Config;
use App\NotifyRule;
use App\UserNotifySetting;

class UserSettingFilter {

    protected $disableChannels;
    protected $enableChannels;
    protected $rules;

    public function __construct(){
        $this->rules = $this->getRules();
    }

    public function apply(array $user_channel){
        $factory = new RuleFactory();
        foreach ($this->rules as $row){
            $rule = $factory->getRule($row['name']);
            $user_channel = $rule->apply($user_channel);
        }
        return $user_channel;
    }

    public function getRules(){
        $rules = NotifyRule::where('status', '=', 1)->get();
        return $rules;
    }

    public function getUserSetting(){


    }

    public static function getUserChannel($user_id){
        return UserNotifySetting::where('user_id', '=', $user_id);
    }



}