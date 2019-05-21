<?php

namespace App\Http\Controllers\Stats;

use App\Models\Movie;
use App\Http\Controllers\Controller;

class MoviesStatsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function countAll()
    {
        $moviesQuantity = Movie::count();

        return response()->json($moviesQuantity);
    }
}
