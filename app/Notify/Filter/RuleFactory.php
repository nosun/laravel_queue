<?php namespace App\Notify\Filter;


class RuleFactory {

    public static function getRule($name){

            switch ($name){
                case 'one':
                    return new One();
                    break;
                case 'two':
                    return new Two();
                case 'three':
                    return new Three();
                    break;
                case 'four':
                    return new Four();
                    break;
                default:
                    throw new Exception("error channel", 1);
                    break;
            }
    }

}