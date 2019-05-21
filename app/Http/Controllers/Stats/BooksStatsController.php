<?php

namespace App\Http\Controllers\Stats;

use App\Models\Book;
use App\Http\Controllers\Controller;

class BooksStatsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function countAll()
    {
        $moviesQuantity = Book::count();

        return response()->json($moviesQuantity);
    }
}
