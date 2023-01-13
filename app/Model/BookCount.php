<?php

declare (strict_types=1);
namespace App\Model;

/**
 */
class BookCount extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'book_counts';
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
    protected $casts = ['id' => 'integer', 'book_type_id' => 'integer', 'book_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}