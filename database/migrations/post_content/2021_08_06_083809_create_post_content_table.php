<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_content', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('relation_id');
            $table->integer('type')->comment('1 => post, 2 => comment');
            $table->text('file_path')->nullable();
            $table->text('file_name')->nullable();
            $table->text('file_mime')->nullable();
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
        Schema::dropIfExists('post_content');
    }
}
