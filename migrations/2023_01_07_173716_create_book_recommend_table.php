<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateBookRecommendTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_recommend', function (Blueprint $table) {
            $table->bigIncrements('book_recommend_id');
            $table->integer('book_id')->comment('推薦小說ID')->unique();
            $table->timestamp('create_time')->useCurrent()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_recommend');
    }
}
