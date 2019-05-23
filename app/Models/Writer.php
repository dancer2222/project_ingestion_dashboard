<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Writer extends Model
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
    protected $table = 'writers';
    public $timestamps = false;
    protected $fillable = ['id', 'name'];

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
    public function movies()
    {
        return $this->belongsToMany(
            Movie::class,
            'movie_writers',
            'writer_id',
            'movie_id',
            'id',
            'id'
        );
    }
}