<?php

namespace App\Http\Controllers\Ingestion\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ingestion\ArrayMovieLicensors;
use Managers\MovieImageManager;
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

    /**
     * @param  Request  $request
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDataFromOMBD(Request $request) {
        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'),
            env('RABBITMQ_LOGIN'), env('RABBITMQ_PASSWORD'));
        $channel = $connection->channel();
        $channel->queue_declare('movieProcessor', false, false, false, false);

        $client = new Client();
        $response = [];
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

            echo $response[$key];

            $msg = new AMQPMessage($response[$key]);
            $channel->basic_publish($msg, '', 'movieProcessor');
        }

        $channel->close();
        $connection->close();

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
