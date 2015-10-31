<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserNotifySetting extends Model
{
    protected $table = 'user_notify_setting';
    public $timestamps = true;

    public function getRulesByUserId($user_id){
        $rules = DB::table('notify_rule')
            ->join('user_notify_setting', 'user_notify_setting.rule_id', '=','notify_rule.id')
            ->where('user_notify_setting.user_id', '=', $user_id)
            ->select('notify_rule.id', 'notify_rule.name')
            ->get();
        return $rules;
    }
}
