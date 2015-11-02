<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class NotifyLog extends Model
{
    protected $table = 'notify_log';
    protected $fillable = array('event_id', 'channel_id', 'level','job','payload','status');
    public $timestamps = true;

}