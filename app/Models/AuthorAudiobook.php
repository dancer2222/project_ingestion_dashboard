<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorAudiobook extends Model
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
    protected $table = 'author_audio_book';
    public $timestamps = false;
}
