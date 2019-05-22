<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MediaMetadata
 * @package App\Models
 */
class MediaMetadata extends Model
{
    /**
     * @var string
     */
    protected $connection = 'content';

    /**
     * @var string
     */
    protected $table = 'media_metadata';

    protected $fillable = ['media_id', 'media_type_id', 'metadata', 'media_type_id'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param $id
     * @param $type
     * @return mixed
     */
    public function getMetadata($id, $type)
    {
        return $this->where('media_id', $id)
            ->where('media_type_id', $type)
            ->select('metadata')
            ->first();
    }
}
