<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->text('description')->after('password')->nullable();
            $table->text('image_profile_path')->after('description')->nullable();
            $table->text('image_profile_file')->after('image_profile_path')->nullable();
            $table->text('image_background_path')->after('image_profile_file')->nullable();
            $table->text('image_background_file')->after('image_background_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
