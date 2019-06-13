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
    public function producers(){
        return $this->belongsToMany(
            Producer::class,
            'movie_producers',
            'movie_id',
            'producer_id',
            'id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function writers(){
        return $this->belongsToMany(
            Writer::class,
            'movie_writers',
            'movie_id',
            'writer_id',
            'id',
            'id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function directors(){
        return $this->belongsToMany(
            Producer::class,
            'movie_directors',
            'movie_id',
            'director_id',
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
            case 'producer':
                $key = !is_numeric($q) ? 'name' : $key;
                $query->whereHas('producers', function ($q) use ($key, $operator, $needle) {
                    $q->where($key, $operator, $needle);
                });

                break;
            case 'director':
                $key = !is_numeric($q) ? 'name' : $key;
                $query->whereHas('directors', function ($q) use ($key, $operator, $needle) {
                    $q->where($key, $operator, $needle);
                });

                break;
            case 'writer':
                $key = !is_numeric($q) ? 'name' : $key;
                $query->whereHas('writers', function ($q) use ($key, $operator, $needle) {
                    $q->where($key, $operator, $needle);
                });

                break;
            case 'brightcove':
                $key = !is_numeric($q) ? 'status' : 'brightcove_Id';
                $query->whereHas('brightcoveId', function ($q) use ($key, $operator, $needle) {
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
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function brightcoveId()
    {
        return $this->hasOne(BrightcoveDb::class, 'id', 'id');
    }

    /**
     * @return mixed
     */
    public function getLastInsertId() {
        return $this->orderBy('id', 'desc')->select('id')->first()->id;
    }
}
