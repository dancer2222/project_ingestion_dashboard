<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSourceProvider extends Model
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
    protected $table = 'data_source_provider';

    public function getAllPaginate() {
        return $this->select('*')->paginate(5);
    }
}
