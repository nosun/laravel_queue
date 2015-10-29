<?php namespace App\Notify\Filter;

use Illuminate\Config;
use App\NotifyRule;
use Illuminate\Support\Facades\DB;

class UserSettingFilter {

    protected $disableChannels;
    protected $enableChannels;
    protected $rules;

    public function __construct($user_id){
        $this->rules = $this->getRules($user_id);
    }

    public function apply(array $user_channels){
        $factory = new RuleFactory();
        foreach ($this->rules as $row){
            $rule = $factory->getRule($row->name);
            $user_channels = $rule->apply($user_channels);
        }
        return $user_channels;
    }

    public function getRules($user_id){
        $rules = DB::table('notify_rule')
            ->join('user_notify_setting', 'user_notify_setting.rule_id', '=','notify_rule.id')
            ->where('user_notify_setting.user_id', '=', $user_id)
            ->select('notify_rule.id', 'notify_rule.name')
            ->get();
        return $rules;
    }

    public function getUserSetting(){


    }

}