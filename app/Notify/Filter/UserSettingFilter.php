<?php namespace App\Notify\Filter;

use Illuminate\Config;
use NotifyRule;

class Filter {

    protected $users;
    protected $disableChannels;
    protected $enableChannels;
    protected $rules;

    public function __construct(array $users){
        $this->users = $users;
        $this->rules = $this->getRules();
    }

    public function getRules(){
        $rules = NotifyRule::all();
        return $rules;
    }

    public function getUserSetting(){


    }

    public function getUserChannel(){



    }



}