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
            $table->string('name');         // 频道名称
            $table->string('display_name'); // 显示名称
            $table->string('description');  // 描述
            $table->timestamps();
        });

        // 通知模板，event_id && channel_id 用于对模板进行分类。
        Schema::create('notify_template', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('event_id')->unsigned();
            $table->integer('channel_id')->unsigned();
            $table->string('path');
            $table->timestamps();
        });

        // 通知日志
        Schema::create('notify_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_id');   // 事件id
            $table->string('channel_id'); // 频道id
            $table->integer('level');     // 事件级别
            $table->string('job');        // 通知Job标识
            $table->integer('status');    // 通知状态
            $table->longText('payload');  // 通知详情
            $table->timestamps();
        });

        // 通知设定 , event_id && channel_id 为unique,手动设置索引
        Schema::create('notify', function (Blueprint $table) {
            $table->integer('event_id')->unsigned();    // 事件id
            $table->integer('channel_id')->unsigned();  // 频道id
            $table->integer('template_id')->unsigned(); // 模板id

            $table->foreign('event_id')->references('id')->on('event')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('channel_id')->references('id')->on('channel')
                ->onUpdate('cascade')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notify_channel');
        Schema::drop('notify_log');
        Schema::drop('notify_template');
        Schema::drop('notify');
    }
}
