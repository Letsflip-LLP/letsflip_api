<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomChannelMessageContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_channel_message_content', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('room_channel_message_id');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_mime');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_channel_message_content');
    }
}
