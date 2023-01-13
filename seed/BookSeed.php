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
class BookSeed implements BaseInterface
{
    public function up(): void
    {
        $model = new \App\Model\Book();
        $model->type = 1;
        $model->book_name = '小說測試';
        $model->author = '阿古';
        $model->status = '连载';
        $model->word_num = 100;
        $model->introduction = '測試測試測試測試測試測試測試測試';
        $model->cover_img = '/book/%E5%8E%86%E5%8F%B2%E5%86%9B%E4%BA%8B/%E4%B8%89%E5%9B%BD%EF%BC%9A%E4%BB%8E%E9%9A%90%E9%BA%9F%E5%88%B0%E5%A4%A7%E9%AD%8F%E9%9B%84%E4%B8%BB.jpg';
        $model->latest_chapter = '第一章 測試任务';
        $model->create_time = '2023-01-01 00:00:00';
        $model->update_time = \Carbon\Carbon::tomorrow()->toDateTimeString();
        $model->save();
    }

    public function down(): void
    {
        // \App\Model\Book::where('author', '阿古')->where('book_name', '小說測試')->delete();
        \App\Model\Book::where([
            ['author', '=', '阿古'],
            ['book_name', '=', '小說測試'],
        ])->delete();
    }

    public function base(): bool
    {
        return false;
    }
}
