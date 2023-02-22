<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("order_code", 40) -> nullable();
            $table->string("address_delivery", 100);
            $table->string("payment_method", 50);
            $table->text("notes") -> nullable();
            $table->string("order_status", 40);
            $table->unsignedBigInteger("customer_id");
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("customer_id")
                  ->references("id")
                  ->on("customers")
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
        Schema::dropIfExists('orders');
    }
}
