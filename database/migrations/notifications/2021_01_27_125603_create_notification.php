<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->string('id',50)->primary()->comment('uuid');
            $table->string('classroom_id',50)->nullable()->comment('id in Classrooms');
            $table->string('mission_id',50)->nullable()->comment('id in Missions');
            $table->string('respone_id',50)->nullable()->comment('id in respones');
            $table->string('user_id_from',50)->nullable()->comment('user id make a action');
            $table->string('user_id_to',50)->nullable()->comment('user id receive notification');
            $table->tinyInteger('type')->default(1)->indexes()->comment('(1) New respone from our mission, (2) New Mission from our Classroom (3) Like mission , (4) Like respone , (5) Like comment in mission (6) Like comment in respone, (7) Comment in mission , (8) Replay comment in mission , (9) Comment in respone , (10) Reply comment in respone');
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
        Schema::dropIfExists('notification');
    }
}
