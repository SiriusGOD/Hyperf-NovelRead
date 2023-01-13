<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateBookContentTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_content', function (Blueprint $table) {
            $table->bigIncrements('book_content_id');
            $table->integer('book_id')->comment('小說ID')->index();
            $table->integer('chapter_num')->comment('章節順序');
            $table->string('chapter')->comment('章節標題');
            $table->text('content')->comment('章節內容');
            $table->index(['book_id','chapter']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_content');
    }
}
