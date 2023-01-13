<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateBookTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book', function (Blueprint $table) {
            $table->bigIncrements('book_id');
            $table->integer('type')->unsigned()->comment('小說類型 id')->index();
            $table->string('book_name')->comment('小說名稱');
            $table->string('author')->comment('作者')->index();
            $table->string('status')->comment('小說狀態\n連載\n完本');
            $table->integer('word_num')->comment('小說字數');
            $table->text('introduction')->comment('小說簡介');
            $table->string('cover_img')->comment('小說封面');
            $table->string('latest_chapter')->comment('最新章節');
            $table->timestamps();

            $table->index(['type','author','book_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book');
    }
}
