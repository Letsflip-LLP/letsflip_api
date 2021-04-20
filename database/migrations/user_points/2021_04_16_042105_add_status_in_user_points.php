<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusInUserPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_points', function (Blueprint $table) {
            //
            $table->tinyInteger('status')->after('type')->default(1)->indexes()->comment('(1) Active , (2) Pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_points', function (Blueprint $table) {
            //
        });
    }
}
