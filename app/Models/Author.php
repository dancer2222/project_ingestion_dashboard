<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /**
     * Connection name
     *
     * @var string
     */
    protected $connection = 'content';

    /**
     * @var string
     */
    protected $table = 'author';
    public $timestamps = false;
    protected $fillable = ['id','name'];

    const NAME_MAX_LENGTH = 50;

    public function getIdAttribute($value){
        return (string)$value;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function books()
    {
        return $this->belongsToMany(
            Book::class,
            'book_authors',
            'author_id',
            'book_id',
            'id',
            'id'
        );
    }

}
