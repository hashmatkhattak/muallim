<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerCarts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_carts', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->tinyInteger('status')->nullable()->comment = "0 DeActivated, 1 Activated, 2 Deleted";
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_carts');
    }
}
