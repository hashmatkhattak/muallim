<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function(Blueprint $table){
            $table->increments('id');
            $table->integer('vendor_id')->unsigned();
            $table->integer('pro_category_id')->unsigned();
            $table->string('product_name');
            $table->float('price');
            $table->text('description');
            $table->text('product_image');
            $table->tinyInteger('status')->comment = "0 DeActivated, 1 Activated, 2 Deleted";
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('pro_category_id')->references('id')->on('product_categories')->onDelete('cascade');

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
