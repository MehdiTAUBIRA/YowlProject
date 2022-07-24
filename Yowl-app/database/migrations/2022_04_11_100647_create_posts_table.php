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
            $table->increments('id');
            $table->string('title');
            $table->string('category');
            $table->longText('description')->nullable();
            $table->string('picture')->nullable();
            $table->BigInteger('user_id')->unsigned();
            $table->integer('nb_likes')->unsigned();
            $table->string('like_user')->nullable();
            $table->integer('nb_dislikes')->unsigned();
            $table->string('dislike-users')->nullable();
            $table->integer('nb_votes')->unsigned();
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
        Schema::dropIfExists('posts');
    }
}
