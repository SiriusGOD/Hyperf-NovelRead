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
class BookTypeSeed implements BaseInterface
{
    public function up(): void
    {
        $model = new \App\Model\BookType();
        $model->book_type_id = 11;
        $model->book_name = '類型測試';
        $model->save();
    }

    public function down(): void
    {
        \App\Model\BookType::where('author', '阿古')->delete();
    }

    public function base(): bool
    {
        return false;
    }
}
