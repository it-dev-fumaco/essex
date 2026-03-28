<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    // Prefer Laravel's modern FILESYSTEM_DISK, but keep backward compatibility.
    'default' => env('FILESYSTEM_DISK', env('FILESYSTEM_DRIVER', 'local')),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        'upcloud' => [
            'driver' => 's3',
            'key' => env('UPCLOUD_ACCESS_KEY_ID'),
            'secret' => env('UPCLOUD_SECRET_ACCESS_KEY'),
            'region' => env('UPCLOUD_DEFAULT_REGION'),
            'bucket' => env('UPCLOUD_BUCKET'),
            'endpoint' => env('UPCLOUD_ENDPOINT'),
            'use_path_style_endpoint' => (bool) env('UPCLOUD_USE_PATH_STYLE_ENDPOINT', true),
            // Build a usable public base URL when UPCLOUD_URL isn't explicitly set.
            // With `use_path_style_endpoint=true`, URLs are typically: {endpoint}/{bucket}/{key}
            'url' => env('UPCLOUD_URL') ?: rtrim((string) env('UPCLOUD_ENDPOINT'), '/').'/'.(string) env('UPCLOUD_BUCKET'),
            'throw' => false,
        ],

    ],

];
