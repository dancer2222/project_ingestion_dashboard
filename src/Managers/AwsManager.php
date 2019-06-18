<?php


namespace Managers;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;

/**
 * Class AwsManager
 * @package Managers
 */
class AwsManager
{
    /**
     * @param $object
     * @param $folder
     * @param $s3Client
     *
     * @return false|string
     */
    public static function checkObjectExists($object, $folder, $s3Client) {
        try {
//            $result = $s3Client->doesObjectExist(env('AWS_BUCKET'),
//                $folder.'/Movies/'.$object);
            $result = $s3Client->doesObjectExist(env('AWS_BUCKET'),
               'ALEX/'.$object);
        } catch (AwsException $exception) {
            return json_encode($exception->getMessage());
        }

        return $result;
    }

    /**
     * @param $object
     * @param  S3Client  $s3Client
     *
     * @return \Aws\Result|false|string
     */
    public static function uploadObject($object, S3Client $s3Client) {
        $s3Key = 'ALEX/'.$object;

        try {
            $result = $s3Client->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $s3Key,
                'SourceFile' => $object,
            ]);
        } catch (AwsException $exception) {
            return json_encode($exception->getMessage());
        }

        return $result;
    }
}
