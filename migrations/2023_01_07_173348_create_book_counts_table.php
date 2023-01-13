<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateBookCountsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_counts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('book_type_id')->unsigned()->comment('小說類型 id');
            $table->integer('book_id')->unsigned()->comment('小說 id')->index();
            $table->string('ip')->unsigned()->comment('點擊者 ip');
            $table->timestamps();
            $table->index('create_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_counts');
    }
}
