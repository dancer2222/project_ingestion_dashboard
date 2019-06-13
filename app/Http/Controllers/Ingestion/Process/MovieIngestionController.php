<?php

namespace App\Http\Controllers\Ingestion\Process;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ingestion\ArrayMovieLicensors;
use Ingestion\Movie\MovieProcess;
use Managers\MovieImageManager;
use Managers\SpreadSheetManager;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use GuzzleHttp\Client;

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
        return SpreadSheetManager::excelToArray($request->file);
    }

    /**
     * @param  Request  $request
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function getDataFromOMBD(Request $request) {

        $client = new Client();
        $movieProcess = new MovieProcess();

        $response = [];
        $movieData = [];

        foreach ($request->body as $key => $data) {
            $response[$key] = $client->request('post',
                'http://www.omdbapi.com/?t='.$data['title']
                .'&y='.$data['year'].'&apikey='.env('API_MOVIE_KEY'),
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-type' => 'application/json'
                    ]
                ]
                )->getBody();

            $movieData []= $movieProcess->getData($response[$key], $data['src']);

            $this->processImages($data['title'], json_decode($response[$key])->Poster  ?? '');

            //TODO Need to create metadata file process

            //$arrayMovieLicensors = new ArrayMovieLicensors();
            //$filePath = $arrayMovieLicensors->getFolderName($request->licensorName) . '\/Mvd_metadata_20180305TT150255+0000.xlsx';
            //$this->sendMovieMessageToRabit($request->licensorName, $filePath);
        }

        $localMetadataFilePath = $this->setMetadataFileName($request->licensorName);
        SpreadSheetManager::arrayToExcel($movieData, $localMetadataFilePath);

        return $request->body;
    }

    /**
     * @param string $providerName
     * @param string $metadataFilePath
     */
    private function sendMovieMessageToRabit ($providerName='MVDEntertainment', $metadataFilePath='mvd\/Mvd_metadata_20180305TT150255+0000.xlsx') {

        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'),
            env('RABBITMQ_LOGIN'), env('RABBITMQ_PASSWORD'));
        $channel = $connection->channel();
        //$channel->queue_declare('ingestion-feed', false, false, false, false);

        $messageData = [
            'message'=>[
                'source' => $providerName,
                'mediaType' => "Movies",
                'feedType' => "Delta",
                'extra' => [
                    'endTask' => true,
                    'taskName' => "Reader",
                    'filePath' =>$metadataFilePath,
                ]
            ],
        ];

        $msg = new AMQPMessage(json_encode($messageData));
        $channel->basic_publish($msg, '', 'ingestion-feed');

        $channel->close();
        $connection->close();
    }

    /**
     * @param $title
     * @param $urlImage
     *
     * @return mixed
     */
    public function processImages($title, $urlImage) {
        $movieImageManager = new MovieImageManager();
        $img = $movieImageManager->convertImage($title, $urlImage);

        return $img[200]->response('jpg');
    }

    /**
     * Set local metadata file name for ingestion
     *
     * @param string $licensorName
     * @return string $localMetadataFilePath
     */
    private function setMetadataFileName ($licensorName) {

        $dataMetaDataFile = Carbon::now()->toDateTimeString();

        $pattern = '/[:-]/';
        $dataMetaDataFile = preg_replace($pattern, '', $dataMetaDataFile);

        $pattern = '/ /';
        $dataMetaDataFile = preg_replace($pattern, '_', $dataMetaDataFile);

        $localMetadataFilePath = $licensorName . "_Metadata_" . $dataMetaDataFile .'.xlsx';

        return $localMetadataFilePath;
    }
}
