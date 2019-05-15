<?php

namespace App\Providers;

use Aws\S3\S3Client;
use Illuminate\Support\ServiceProvider;

class AwsServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     *
     */
    public function register()
    {
        $this->app->singleton(S3Client::class, function ($app) {
            $s3 = new S3Client([
                                       'version' => 'latest',
                                       'region' => 'us-east-1',
                                       'credentials' => [
                                               'key' => env('AWS_ACCESS_KEY_ID'),
                                               'secret' => env('AWS_SECRET_ACCESS_KEY'),
                                       ],
                               ]);

            return $s3;
        });
    }

    /**
     * @return array
     */
    public function boot()
    {
        return [S3Client::class];
    }
}
