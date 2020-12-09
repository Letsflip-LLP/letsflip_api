<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title',225)->nullable();
            $table->text('text')->nullable();
            $table->text('file_path')->nullable();
            $table->text('file_name')->nullable();
            $table->text('file_mime')->nullable();
            $table->smallInteger('type')->default(1)->comment("(1) Public Classroom (2) Private Classroom (3) Master Classroom");
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
        Schema::dropIfExists('classrooms');
    }
}
