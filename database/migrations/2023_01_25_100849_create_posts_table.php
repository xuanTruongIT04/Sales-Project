<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.  
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string("post_title", 300);
            $table->text("post_desc");
            $table->text("post_content");
            $table->string("slug", 500);
            $table->string("post_status")->  default("pending");
            $table->unsignedBigInteger("post_cat_id");
            $table->unsignedBigInteger("image_id");
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("post_cat_id")
            ->references("id")
            ->on("post_cats")
            ->onDelete("cascade")
            ->onUpdate("cascade"); 
            $table->foreign("image_id")
                ->references("id")
                ->on("images")
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
        Schema::dropIfExists('posts');
    }
}
