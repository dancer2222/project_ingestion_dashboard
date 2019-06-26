<?php

namespace App\Http\Controllers\Ingestion\Process;

use App\Http\Controllers\Controller;
use Aws\S3\S3Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Ingestion\ArrayMovieLicensors;
use Ingestion\Movie\MovieProcess;
use Managers\AwsManager;
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
    public function indexIngestMovies()
    {
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
    public function convertMetadataFile(Request $request)
    {
        return SpreadSheetManager::excelToArray($request->file);
    }

    /**
     * @param  Request  $request
     * @param  S3Client  $awsS3
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function getDataFromOMBD(Request $request, S3Client $awsS3)
    {
        $client = new Client();
        $movieProcess = new MovieProcess();

        $response = [];
        $moviesData = [];

        foreach ($request->body as $key => $data) {
            $response[$key] = $client->request('post',
                'http://www.omdbapi.com/?t='.$data['title'].'asd'
                .'&y='.$data['year'].'&apikey='.env('API_MOVIE_KEY'),
                [
                    'headers' => [
                        'Accept'       => 'application/json',
                        'Content-type' => 'application/json',
                    ],
                ]
            )->getBody();

            if (json_decode($response[$key])->Response === 'False') {
                $response[$key] = $client->request('post',
                    'http://www.omdbapi.com/?t='.$data['title']
                    .'&apikey='.env('API_MOVIE_KEY'),
                    [
                        'headers' => [
                            'Accept'       => 'application/json',
                            'Content-type' => 'application/json',
                        ],
                    ]
                )->getBody();
            }

            $currentMovie = $movieProcess->getData($response[$key], $data['src'], $data);
            $this->processImages($currentMovie['cover file name'], json_decode($response[$key])->Poster ?? '');

            AwsManager::uploadObject($currentMovie['cover file name'], $awsS3);
            unlink($currentMovie['cover file name']);

            $moviesData[] = $currentMovie;
        }

        $localMetadataFilePath = $this->setMetadataFileName($request->licensorName);
        SpreadSheetManager::arrayToExcel($moviesData, $localMetadataFilePath);
        AwsManager::uploadObject($localMetadataFilePath, $awsS3);
        unlink($localMetadataFilePath);

        $arrayMovieLicensors = new ArrayMovieLicensors();
        $filePath = $arrayMovieLicensors->getFolderName($request->licensorName)."/".$localMetadataFilePath;
        $this->sendMovieMessageToRabit($request->licensorName, $filePath);

        return $request->body;
    }

    /**
     * @param  string  $providerName
     * @param  string  $metadataFilePath
     */
    private function sendMovieMessageToRabit(
        $providerName = 'MVDEntertainment',
        $metadataFilePath = 'mvd\/Mvd_metadata_20180305TT150255+0000.xlsx'
    ) {

        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'),
            env('RABBITMQ_LOGIN'), env('RABBITMQ_PASSWORD'));
        $channel = $connection->channel();
        //$channel->queue_declare('ingestion-feed', false, false, false, false);

        $messageData = [
            'message' => [
                'source'    => $providerName,
                'mediaType' => "Movies",
                'feedType'  => "Delta",
                'extra'     => [
                    'endTask'  => true,
                    'taskName' => "Reader",
                    'filePath' => $metadataFilePath,
                ],
            ],
        ];

        $msg = new AMQPMessage(json_encode($messageData));
        $channel->basic_publish($msg, '', 'ingestion-feed');

        $channel->close();
        $connection->close();
    }

    /**
     * @param  string  $coverName
     * @param $urlImage
     *
     * @return mixed
     */
    public function processImages($coverName, $urlImage)
    {

        $movieImageManager = new MovieImageManager();
        $img = $movieImageManager->convertImage($coverName, $urlImage);

        return $img[200]->response('jpg');
    }

    /**
     * Set local metadata file name for ingestion
     *
     * @param  string  $licensorName
     *
     * @return string $localMetadataFilePath
     */
    private function setMetadataFileName($licensorName)
    {

        $dataMetaDataFile = Carbon::now()->toDateTimeString();

        $dataMetaDataFile = preg_replace('/[:-]/', '', $dataMetaDataFile);

        $dataMetaDataFile = preg_replace('/ /', '_', $dataMetaDataFile);

        $localMetadataFilePath = $licensorName."_Metadata_".$dataMetaDataFile.'.xlsx';

        return $localMetadataFilePath;
    }

    /**
     * @param  Request  $request
     * @param  S3Client  $awsS3
     *
     * @return false|string
     */
    public function checkMovieForAwsBucket(Request $request, S3Client $awsS3)
    {
        $arrayMovieLicensors = new ArrayMovieLicensors();

        $result = AwsManager::checkObjectExists(
            $request->body,
            $arrayMovieLicensors->getFolderName($request->folder),
            $awsS3
        );

        return json_encode($result);
    }
}
