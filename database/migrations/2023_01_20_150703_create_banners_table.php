<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string("banner_name", 300);
            $table->string("banner_status")->  default("pending");
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
        Schema::dropIfExists('banners');
    }
}
