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
use Aws\Laravel\AwsFacade;

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
        $s3 = AwsFacade::createClient('s3');

        $response = [];
        $moviesData = [];

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

            $currentMovie = $movieProcess->getData($response[$key], $data['src']);
            $this->processImages($currentMovie['cover_file_name'], json_decode($response[$key])->Poster  ?? '');

            $this->uploadToAWS($s3, $moviesData['cover_file_name']);

            $moviesData[] = $currentMovie;
        }

        $localMetadataFilePath = $this->setMetadataFileName($request->licensorName);
        SpreadSheetManager::arrayToExcel($moviesData, $localMetadataFilePath);
        $this->uploadToAWS($s3, $localMetadataFilePath);

        //$arrayMovieLicensors = new ArrayMovieLicensors();
        //$filePath = $arrayMovieLicensors->getFolderName($request->licensorName) . '\/Mvd_metadata_20180305TT150255+0000.xlsx';
        //$this->sendMovieMessageToRabit($request->licensorName, $filePath);

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
     * @param string $coverName
     * @param $urlImage
     *
     * @return mixed
     */
    public function processImages($coverName, $urlImage) {

        $movieImageManager = new MovieImageManager();
        $img = $movieImageManager->convertImage($coverName, $urlImage);

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

        $dataMetaDataFile = preg_replace('/[:-]/', '', $dataMetaDataFile);

        $dataMetaDataFile = preg_replace('/ /', '_', $dataMetaDataFile);

        $localMetadataFilePath = $licensorName . "_Metadata_" . $dataMetaDataFile .'.xlsx';

        return $localMetadataFilePath;
    }

    /**
     * @param $s3
     * @param string $metadataFileName
     * @throws \Exception
     */
    private function uploadToAWS($s3, $fileName) {

        $s3Key = 'ALEX/' . $fileName;

        try {
            $s3->putObject(array(
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $s3Key,
                'SourceFile' => $fileName,
            ));
        } catch (\Exception $e) {

        }
    }
}
