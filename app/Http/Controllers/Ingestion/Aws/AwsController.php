<?php

namespace App\Http\Controllers\Ingestion\Aws;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class AwsController
 *
 * @package App\Http\Controllers\Aws
 */
class AwsController extends Controller
{
    /**
     * @var
     */
    private $s3;

    /**
     * AwsController constructor.
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct() {
        $this->s3 = app()->make('aws')->createClient('s3');
    }

    /**
     * @param  Request  $request
     *
     * @return false|string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function checkMovieForAwsBucket(Request $request) {

        try {
            $result = $this->s3->doesObjectExist(env('AWS_BUCKET'),
                $request->folder.'/Movies/'.$request->body);
        } catch (Exception $exception) {
            return json_encode($exception->getMessage());
        }

        return json_encode($result);
    }
}