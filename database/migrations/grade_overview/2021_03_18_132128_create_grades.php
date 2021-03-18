<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_overviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('mission_response_id')->index(); 
            $table->longText('text')->nullable();
            $table->smallInteger('quality')->nullable();
            $table->smallInteger('creativity')->nullable();
            $table->smallInteger('language')->nullable();
            $table->integer('point')->nullable();
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
        Schema::dropIfExists('grades');
    }
}
