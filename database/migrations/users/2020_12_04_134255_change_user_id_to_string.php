<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUserIdToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) { $table->uuid('id')->change();});
        Schema::table('oauth_auth_codes', function (Blueprint $table) { $table->uuid('user_id')->change();});
        Schema::table('oauth_access_tokens', function (Blueprint $table) { $table->uuid('user_id')->change();}); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('string', function (Blueprint $table) {
            //
        });
    }
}
