<?php namespace App\Notify\Channel;

use Exception;

class ChannelFactory {

    public static function CreateChannel($channel){
        switch ($channel){
            case 'email':
                return new EmailChannel();
                break;
            case 'sms':
                return new smsChannel();
            case 'wechat':
                return new weChatChannel();
                break;
            case 'siteMsg':
                return new siteMsgChannel();
                break;
            case 'default':
            default:
                throw new Exception("error channel", 1);
                break;
        }
    }
}