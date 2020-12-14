<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionResponses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index()->comment("id in users");
            $table->uuid('mission_id')->index()->comment("id in missions");
            $table->string('title',225);
            $table->text('text'); 
            $table->uuid('default_content_id')->comment('id in mission_contents');
            $table->smallInteger('status')->comment("(1) Publish (2) Draft (3) Archived")->indexes();
            $table->smallInteger('type')->comment("(1) Public Response (2) Private Response")->indexes();
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
        Schema::dropIfExists('mission_responses');
    }
}
