<?php namespace App\Channel;

use Exception;

class ChannelFactory {

    public static function CreateChannel($channel){
        switch ($channel){
            case 1: // email
                return new EmailChannel();
                break;
            case 2: // wechat
                return new smsChannel();
            case 3: // sms
                return new weChatChannel();
                break;
            case 4: // siteMsg
                return new siteMsgChannel();
                break;
            case 'default':
            default:
                throw new Exception("error channel", 1);
                break;
        }
    }
}