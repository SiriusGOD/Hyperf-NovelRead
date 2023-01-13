<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $book_id 
 * @property int $type 
 * @property string $book_name 
 * @property string $author 
 * @property string $status 
 * @property int $word_num 
 * @property string $introduction 
 * @property string $cover_img 
 * @property string $latest_chapter 
 * @property string $create_time 
 * @property string $update_time 
 */
class Book extends Model
{
    public $timestamps = false;
    
    //每頁筆數
    public const PAGE_PER = 10;

    //前端每頁筆數
    public const FRONTEND_PAGE_PER = 30;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'book';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['book_id' => 'integer', 'type' => 'integer', 'word_num' => 'integer'];
}