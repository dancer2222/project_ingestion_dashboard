<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
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
    protected $table = 'movie';
    public $timestamps = false;

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
}
