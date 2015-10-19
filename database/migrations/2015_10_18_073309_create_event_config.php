<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('event_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('level');
            $table->longtext('description');
            $table->boolean('channel_sms');
            $table->string('template_sms');
            $table->boolean('channel_wx');
            $table->string('template_wx');
            $table->boolean('channel_mail');
            $table->string('template_mail');
            $table->boolean('channel_site');
            $table->string('template_site');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('event_config');
    }
}
