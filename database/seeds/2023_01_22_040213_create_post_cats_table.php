<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_cats', function (Blueprint $table) {
            $table->id();
            $table->string("post_cat_title", 300);
            $table->string("slug", 500);
            $table->integer("level");
            $table->string("post_cat_status")->default("pending");
            $table->integer("cat_parent_id");
            $table->softDeletes();
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
        Schema::dropIfExists('post_cats');
    }
}
