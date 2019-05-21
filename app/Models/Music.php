<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Music extends Model
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
    protected $table = 'music';

    /**
     * @var array
     */
    protected $fillable = ['id', 'title', 'artist_name'];

    /**
     * @param $id
     * @return array
     */
    public function getTrackById($id)
    {
        return $this->where('id', $id)->get();
    }

    /**
     * @param $id
     * @return array
     */
    public function getMusicByAlbumId($id)
    {
        return $this->where('album_id', $id)->select(['id', 'title', 'artist_name'])->get();
    }
}
