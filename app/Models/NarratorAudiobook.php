<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NarratorAudiobook extends Model
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
    protected $table = 'narrator_audio_book';
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
    public function audiobooks()
    {
        return $this->belongsToMany(
            Audiobook::class,
            'audio_book_narrators',
            'narrator_id',
            'audio_book_id',
            'id',
            'id'
        );
    }
}