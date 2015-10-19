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
        Schema::create('notify', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('channel_id');
            $table->string('event_id');
            $table->integer('level');
            $table->integer('status');
            $table->longText('payload');
            $table->timestamps();
        });

        Schema::create('channel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('event_channel', function (Blueprint $table) {
            $table->integer('event_id')->unsigned();
            $table->integer('channel_id')->unsigned();

            $table->foreign('event_id')->references('id')->on('event')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('channel_id')->references('id')->on('channel')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('notify_template', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('event_id')->unsigned();
            $table->integer('channel_id')->unsigned();
            $table->string('path');
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
        Schema::drop('notify');
        Schema::drop('event_channel');
        Schema::drop('channel');
        Schema::drop('notify_template');
    }
}
