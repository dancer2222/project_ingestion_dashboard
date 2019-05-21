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
    public $timestamps = false;
    protected $fillable = ['id', 'isbn'];

    public function getIdAttribute($value){
        return (string)$value;
    }

    public function getIsbnAttribute($value){
        return (string)$value;
    }

    public function authors(){
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
     * @return LengthAwarePaginator
     */
    public function search()
    {
        $request = request();
        $query = $this->newQuery();
        $q = $request->get('q', '');

        if ($q) {
            if (is_numeric($q)) {
                $query->select('id', 'title', 'isbn', 'status')->where('id', $q)
                    ->orWhere('isbn', $q);
            } elseif (is_string($q)) {
                $query->select('id', 'title', 'isbn', 'status')->where('title', 'like', "%$q%");
            }
        }

        $books = $query->paginate();

        return $books;
    }

}
