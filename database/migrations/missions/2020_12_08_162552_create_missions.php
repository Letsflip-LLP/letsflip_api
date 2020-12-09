<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index()->comment("id in users");
            $table->string('title',225);
            $table->text('text'); 
            $table->uuid('default_content_id')->comment('id in mission_contents');
            $table->smallInteger('status')->comment("(1) Publish (2) Draft (3) Archived")->indexes();
            $table->smallInteger('type')->comment("(1) Public Mission (2) Private Mission")->indexes();
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
        Schema::dropIfExists('missions');
    }
}
