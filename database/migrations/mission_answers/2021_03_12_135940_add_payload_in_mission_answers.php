<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayloadInMissionAnswers extends Migration
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
            $table->longText('payload')->after('is_true')->nullable()->comment('Current payload when user answer the question');
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
