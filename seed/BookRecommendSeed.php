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
class BookRecommendSeed implements BaseInterface
{
    public function up(): void
    {
        $model = new \App\Model\BookRecommend();
        $model->book_id = 101;
        $model->create_time = '2023:01:01';
        $model->save();
    }

    public function down(): void
    {
        \App\Model\BookRecommend::where('book_id', 101)->delete();
    }

    public function base(): bool
    {
        return false;
    }
}
