<?php

namespace App\Http\Controllers\Ingestion\Process;

use App\Http\Controllers\Controller;

class IngestionController extends Controller
{
    public function indexIngestMovies() {
        return view('ingestion.metadataForm');
    }
}
