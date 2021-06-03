<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBlocked extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_blocked', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('uuid');
            $table->uuid('user_id_from')->comment('user id make a action');
            $table->uuid('user_id_to')->comment('user id bloced');
            $table->tinyInteger('status')->default(1)->indexes()->comment('(1) active');
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
        Schema::dropIfExists('user_blocked');
    }
}
