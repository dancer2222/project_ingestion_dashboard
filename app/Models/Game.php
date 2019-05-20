<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
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
    protected $table = 'game';
    public $timestamps = false;

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
                $query->where('id', $q);
            } elseif (is_string($q)) {
                $query->where('title', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%");
            }
        }

        $movies = $query->paginate();

        return $movies;
    }
}
