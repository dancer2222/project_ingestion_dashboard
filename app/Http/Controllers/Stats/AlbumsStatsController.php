<?php

namespace App\Http\Controllers\Stats;

use App\Models\Album;
use App\Http\Controllers\Controller;

class AlbumsStatsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function countAll()
    {
        $albumsQuantity = Album::count();

        return response()->json($albumsQuantity);
    }
}
