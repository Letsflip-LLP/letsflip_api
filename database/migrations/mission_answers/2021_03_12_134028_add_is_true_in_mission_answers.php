<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsTrueInMissionAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mission_answers', function (Blueprint $table) { 
            //
            $table->smallInteger('is_true')->after('answer')->default(0)->comment('(0) Answer False , (1) Answer true ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mission_answers', function (Blueprint $table) {
            //
        });
    }
}
