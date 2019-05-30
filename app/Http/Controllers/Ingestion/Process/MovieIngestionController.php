<?php

namespace App\Http\Controllers\Ingestion\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ingestion\ArrayMovieLicensors;
use Managers\MovieImageManager;

/**
 * Class MovieIngestionController
 *
 * @package App\Http\Controllers\Ingestion\Process
 */
class MovieIngestionController extends Controller
{
    /**
     * @var string
     */
    public $title = 'they-called-him-amen';

    /**
     * @var string
     */
    public $urlImage = 'https://content.milkbox.com/movie/053/177/they-called-him-amen-200x282.jpg';

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexIngestMovies() {
        $arrayMovieLicensors = new ArrayMovieLicensors();

        return view('ingestion.metadataForm', ['licensorNames' => $arrayMovieLicensors->getLicensorNames()]);
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

    /**
     * @return mixed
     */
    public function processImages() {

        $movieImageManager = new MovieImageManager();
        $img = $movieImageManager->convertImage($this->title, $this->urlImage);

        return $img[200]->response('jpg');
    }
}
