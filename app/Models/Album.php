<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
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
    protected $table = 'music_album';
    public $timestamps = false;
    protected $fillable = ['id'];

    public function getIdAttribute($value){
        return (string)$value;
    }
}
