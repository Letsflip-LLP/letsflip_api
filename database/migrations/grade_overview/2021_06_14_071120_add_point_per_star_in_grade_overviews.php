<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPointPerStarInGradeOverviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grade_overviews', function (Blueprint $table) {
            //
            $table->smallInteger('point_per_star')->nullable()->after('point')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grade_overviews', function (Blueprint $table) {
            //
        });
    }
}
