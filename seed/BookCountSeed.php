<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
class BookCountSeed implements BaseInterface
{
    public function up(): void
    {
        $model = new \App\Model\BookCount();
        $model->book_type_id = 11;
        $model->book_id = 1;
        $model->ip = '127.0.0.1';
        $model->save();
    }

    public function down(): void
    {
        \App\Model\BookCount::where('book_type_id', 11)->delete();
    }

    public function base(): bool
    {
        return false;
    }
}
