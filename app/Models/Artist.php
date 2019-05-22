<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
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
    protected $table = 'artists';
    public $timestamps = false;
    protected $fillable = ['id','name'];

    const NAME_MAX_LENGTH = 50;

    /**
     * Mutator for 'id' field
     * @param $value
     * @return string
     */
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
            'book_artists',
            'artist_id',
            'book_id',
            'id',
            'id'
        );
    }
}