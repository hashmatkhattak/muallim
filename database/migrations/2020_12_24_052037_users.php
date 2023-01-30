<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users',function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('email');
            $table->integer('role_id')->unsigned();
            $table->string('phone');
            $table->string('password');
            $table->string('verification_code');
            $table->tinyInteger('status')->comment = "0 DeActivated, 1 Activated, 2 Deleted";
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('user_roles')->onDelete('cascade');
            // $table->foreign('role_id')->references('id')->on('user_roles');
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
