<?php

namespace App\Http\Controllers\Ingestion\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class MovieIngestionController
 *
 * @package App\Http\Controllers\Ingestion\Process
 */
class MovieIngestionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexIngestMovies() {
        return view('ingestion.metadataForm');
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Support\Collection
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function convertMetadataFile(Request $request) {
        return excelToArray($request->file);
    }

    public function getDataFromOMBD(Request $request) {
//        return 'Everything OK';
        return $request->body;
    }
}