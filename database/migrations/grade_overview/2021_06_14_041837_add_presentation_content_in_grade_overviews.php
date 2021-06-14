<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPresentationContentInGradeOverviews extends Migration
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
            $table->smallInteger('presentation')->nullable()->after('language');
            $table->smallInteger('content')->nullable()->after('presentation');
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
