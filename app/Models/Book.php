<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
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
    protected $table = 'book';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id', 'isbn'];

    /**
     * Mutator for 'id' field
     * @param $value
     * @return string
     */
    public function getIdAttribute($value) {
        return (string)$value;
    }

    /**
     * Mutator for 'isbn' field
     * @param $value
     * @return string
     */
    public function getIsbnAttribute($value) {
        return (string)$value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authors() {
        return $this->belongsToMany(
            Author::class,
            'book_authors',
            'book_id',
            'author_id',
            'id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function artists() {
        return $this->belongsToMany(
            Author::class,
            'book_artists',
            'book_id',
            'artist_id',
            'id',
            'id'
        );
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
    public function publisher()
    {
        return $this->belongsToMany(
            Publisher::class,
            'book_publishers',
            'book_id',
            'publisher_id',
            'id',
            'id'
        );
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
                $query->whereHas('authors', function ($q) use ($key, $operator, $needle) {
                    $q->where($key, $operator, $needle);
                });

                break;
            case 'artist':
                $key = !is_numeric($q) ? 'name': $key;
                $query->whereHas('artists', function ($q) use ($key, $operator, $needle) {
                    $q->where($key, $operator, $needle);
                });

                break;
            case 'publisher':
                $key = !is_numeric($q) ? 'name': $key;
                $query->whereHas('publisher', function ($q) use ($key, $operator, $needle) {
                    $q->where($key, $operator, $needle);
                });

                break;
            default:
                $key = !is_numeric($q) ? 'title': $key;

                $query->select('id', 'title', 'isbn', 'status')
                    ->where($key, $operator, $needle)
                    ->orWhere('isbn', $operator, $needle);
        }

        $books = $query->paginate();

        return $books;
    }
}
