<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    /**
     * Mutator for 'id' field
     * @param $value
     * @return string
     */
    public function getIdAttribute($value)
    {
        return (string)$value;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function search()
    {
        $request = request();
        $query = $this->newQuery();

        $q = $request->get('q', '');
        $searchBy = $request->get('search_by', 'default');

        $operator = is_numeric($q) ? '=' : 'like';
        $needle = is_numeric($q) ? $q : "%$q%";
        $key = 'id';

        switch ($searchBy) {
            case 'licensor':
                $key = !is_numeric($q) ? 'name' : $key;
                $query->whereHas('licensor', function ($q) use ($key, $operator, $needle) {
                    $q->where($key, $operator, $needle);
                });

                break;
            case 'author':
                $key = !is_numeric($q) ? 'name': $key;
                $query->whereHas('authorsAudiobooks', function ($q) use ($key, $operator, $needle) {
                    $q->where($key, $operator, $needle);
                });

                break;
            case 'dataOriginId':
                $query->where('data_origin_id', $operator, $needle);

                break;
            case 'publisher':
                $key = !is_numeric($q) ? 'name': $key;
                $query->whereHas('publishersAudiobooks', function ($q) use ($key, $operator, $needle) {
                    $q->where($key, $operator, $needle);
                });

                break;
            case 'narrator':
                $key = !is_numeric($q) ? 'name': $key;
                $query->whereHas('narratorsAudiobooks', function ($q) use ($key, $operator, $needle) {
                    $q->where($key, $operator, $needle);
                });

                break;
            default:
                $key = !is_numeric($q) ? 'title': $key;
                $query->where($key, $operator, $needle);
        }

        $movies = $query->paginate();

        return $movies;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function licensor()
    {
        return $this->belongsTo(Licensor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authorsAudiobooks()
    {
        return $this->belongsToMany(
            AuthorAudiobook::class,
            'audio_book_authors',
            'audio_book_id',
            'author_id',
            'id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function publishersAudiobooks()
    {
        return $this->belongsToMany(
            Publisher::class,
            'audio_book_publishers',
            'audio_book_id',
            'publisher_id',
            'id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function narratorsAudiobooks()
    {
        return $this->belongsToMany(
            NarratorAudiobook::class,
            'audio_book_narrators',
            'audio_book_id',
            'narrator_id',
            'id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(
            ProductAudioBook::class,
            'audio_book_products',
            'audio_book_id',
            'product_id',
            'id',
            'id'
        )->withPivot('subscription_type');
    }
}
