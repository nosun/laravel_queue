<?php namespace App\Notify\Filter;

interface RuleInterface {

    public function apply(array $user_Channel);

}