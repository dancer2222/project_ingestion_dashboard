<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrightcoveDb extends Model
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
    protected $table = 'brightcove';
    protected $fillable = ['id'];

}