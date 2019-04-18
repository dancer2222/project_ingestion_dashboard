<?php

namespace App\Http\Controllers\Ingestion\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class IngestionController
 *
 * @package App\Http\Controllers\Ingestion\Process
 */
class IngestionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexIngestMovies() {
        return view('ingestion.metadataForm');
    }

    public function getDataFromForm(Request $request) {

        die();
    }
}
