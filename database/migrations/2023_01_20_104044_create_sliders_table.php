<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string("slider_name", 300);
            $table->string("slider_status")->  default("pending");
            $table->unsignedBigInteger("image_id");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("image_id")
            ->references("id")->on("images")
            ->onDelete("cascade") -> onUpdate("cascade"); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sliders');
    }
}
