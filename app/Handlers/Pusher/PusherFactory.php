<?php namespace App\Handlers\Pusher;

use Exception;

class PusherFactory {

    public static function createPusher($channel){
        switch ($channel){
            case 'email':
                return new EmailPusher();
                break;
            case 'sms':
                return new smsPusher();
            case 'wechat':
                return new weChatPusher();
                break;
            case 'siteMsg':
                return new siteMsgPusher();
                break;
            case 'default':
            default:
                throw new Exception("error channel", 1);
                break;
        }
    }
}