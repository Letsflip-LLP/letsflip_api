<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->string('id',50)->primary()->comment('uuid');
            $table->uuid('user_id')->indexes();
            $table->string('module',225)->indexes('name of table');
            $table->uuid('module_id')->indexes();
            $table->string('feeling',225)->indexes('code of emoth')->nullable();
            $table->string('title',225)->nullable();
            $table->string('text',225)->nullable();
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
        Schema::dropIfExists('reviews');
    }
}
