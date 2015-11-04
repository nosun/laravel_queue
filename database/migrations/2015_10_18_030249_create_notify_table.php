<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // 通知通道
        Schema::create('notify_channel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');                     // 频道名称
            $table->string('display_name');             // 显示名称
            $table->string('description');              // 描述
            $table->tinyInteger('status');              // 开关
            $table->timestamps();
        });

        // 通知模板，event_id && channel_id 用于对模板进行分类。
        Schema::create('notify_template', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');                     // 名称
            $table->string('path');                     // 模板路径
            $table->integer('event_id')->unsigned();    // 事件id
            $table->integer('channel_id')->unsigned();
            $table->timestamps();
        });

        // 通知日志
        Schema::create('notify_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type');               // 事件类别 '通知:1;待办:2'
            $table->tinyInteger('level');              // 事件级别 '普通:1;重要:2;紧急:3'
            $table->integer('event_id')->unsigned();   // 事件id
            $table->integer('channel_id')->unsigned(); // 频道id
            $table->string('job');                     // 通知Job标识 'queue:job,not queue:null'
            $table->tinyInteger('status');             // 通知状态
            $table->longText('payload');               // 通知详情
            $table->timestamps();
        });

        // 通知设定 , event_id && channel_id 为unique,手动设置索引
        Schema::create('notify', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();    // 事件id
            $table->integer('channel_id')->unsigned();  // 频道id
            $table->integer('template_id')->unsigned(); // 模板id
            $table->tinyInteger('status');              // 状态，开关

//            $table->foreign('event_id')->references('id')->on('event')
//                ->onUpdate('cascade')->onDelete('cascade');                 // 如果事件删除，事件的所有通道清除，尽量用status
//            $table->foreign('channel_id')->references('id')->on('notify_channel')  // 如果通道删除，通道的所有事件清除，尽量用status
//            ->onUpdate('cascade')->onDelete('cascade');
        });

        // 通知规则
        Schema::create('notify_rule', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');         // 频道名称
            $table->string('display_name'); // 显示名称
            $table->string('description');  // 描述
            $table->tinyInteger('status');  // 开关
        });

        // 用户通知设定
        Schema::create('user_notify_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();    // 用户id
            $table->integer('rule_id')->unsigned();    // 规则id
            $table->tinyInteger('status');             // 状态，开关

//            $table->foreign('user_id')->references('id')->on('users')        // 如果用户删除，用户的所有规则清除
//                ->onUpdate('cascade')->onDelete('cascade');
//            $table->foreign('rule_id')->references('id')->on('notify_rule')  // 如果规则清除，用户的所有规则清除
//                ->onUpdate('cascade')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_notify_setting');
        Schema::drop('notify_rule');
        Schema::drop('notify');
        Schema::drop('notify_log');
        Schema::drop('notify_template');
        Schema::drop('notify_channel');
    }
}
