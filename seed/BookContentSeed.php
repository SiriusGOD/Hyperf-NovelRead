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
class BookContentSeed implements BaseInterface
{
    public function up(): void
    {
        $model = new \App\Model\BookContent();
        $model->book_id = 1;
        $model->chapter_num = 1;
        $model->chapter = '第一章 測試任务';
        $model->content = 'testestestestestestest';
        $model->save();
    }

    public function down(): void
    {
        \App\Model\BookContent::where('chapter', '=', '第一章 測試任务')->delete();
    }

    public function base(): bool
    {
        return false;
    }
}
