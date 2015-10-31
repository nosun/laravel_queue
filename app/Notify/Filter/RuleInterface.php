<?php namespace App\Notify\Filter;

interface RuleInterface {

    public function apply($user_Channels,$user_rules);

}