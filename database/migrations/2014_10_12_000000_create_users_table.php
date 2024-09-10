<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('uin', 9)->nullable()->unique();
            $table->string('netid')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->longText('token')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            /*
            $table->longText('access_token')->nullable();
            $table->longText('id_token')->nullable();
            $table->longText('refresh_token')->nullable();
            */

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
