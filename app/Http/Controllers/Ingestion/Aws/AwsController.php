<?php

namespace App\Http\Controllers\Ingestion\Aws;

use Aws\S3\S3Client;
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
     * @param Request $request
     *
     * @return false|string
     */
    public function checkMovieForAwsBucket(Request $request)
    {
        $s3Client = new S3Client(
                [
                        'key'     => env('AWS_ACCESS_KEY_ID', ''),
                        'secret'  => env('AWS_SECRET_ACCESS_KEY', ''),
                        'region'  => env('AWS_DEFAULT_REGION'),
                        'version' => 'latest',
                ]

        );

        try {
            $result = $s3Client->doesObjectExist(env('AWS_BUCKET'),
                                                 $request->folder . '/Movies/' . $request->body);
        } catch (Exception $exception) {
            return json_encode($exception->getMessage());
        }

        return json_encode($result);
    }
}
