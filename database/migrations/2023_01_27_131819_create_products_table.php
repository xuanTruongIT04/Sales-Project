<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("product_code", 20);
            $table->string("product_name", 150);
            $table->string("slug", 500)->unique;
            $table->integer("price_old");
            $table->integer("price_new")-> nullable();
            $table->integer("qty_sold")-> nullable();
            $table->integer("qty_remain");
            $table->text("product_detail");
            $table->text("product_desc");
            $table->string("product_status", 50)->default("pending");
            $table->enum('sold_most', ['0', '1'])->default("0");
            $table->unsignedBigInteger("product_cat_id");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("product_cat_id")
                  ->references("id")
                  ->on("product_cats")
                  ->onDelete("cascade")
                  ->onUpdate("cascade");
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
