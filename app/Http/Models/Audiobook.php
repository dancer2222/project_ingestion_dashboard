<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audiobook extends Model
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
    protected $table = 'audio_book';
    public $timestamps = false;
}
