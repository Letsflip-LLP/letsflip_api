<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_points', function (Blueprint $table) {
            $table->uuid('id',50)->primary()->comment('uuid');
            $table->integer('value');
            $table->uuid('classroom_id',50)->nullable()->comment('id in Classrooms');
            $table->uuid('mission_id',50)->nullable()->comment('id in Missions');
            $table->uuid('respone_id',50)->nullable()->comment('id in respones');
            $table->uuid('user_id_from',50)->nullable()->comment('user id make a action');
            $table->uuid('user_id_to',50)->nullable()->comment('user id receive notification');
            $table->uuid('mission_comment_id')->nullable();
            $table->uuid('respone_comment_id')->nullable();
            $table->tinyInteger('type')->default(1)->indexes()->comment('(1) From first mission , (2) From next mission , (3) For Respond a mission');
            $table->dateTime('read_at')->nullable();
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
        Schema::dropIfExists('user_points');
    }
}
