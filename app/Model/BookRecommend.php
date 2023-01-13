<?php

declare (strict_types=1);
namespace App\Model;

/**
 */
class BookRecommend extends Model
{
    public const PAGE_PER = 10;
    
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'book_recommend';
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
    protected $casts = ['book_id' => 'integer'];
}