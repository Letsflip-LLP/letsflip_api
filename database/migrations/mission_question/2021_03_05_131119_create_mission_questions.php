<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('mission_id')->index("id in missions");
            $table->string('title',225)->nullable();
            $table->text('text')->nullable();
            $table->text('option1')->nullable();
            $table->text('option2')->nullable();
            $table->text('option3')->nullable();
            $table->text('option4')->nullable();
            $table->text('option5')->nullable();
            $table->text('option6')->nullable();
            $table->text('option7')->nullable();
            $table->string('correct_option',225)->nullable();
            $table->smallInteger('question_type')->default(1)->comment("(1) Multiple Choise");
            $table->smallInteger('type')->default(1)->comment("(1) Quick Scores , (2) Learning Journey");
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
        Schema::dropIfExists('mission_questions');
    }
}
