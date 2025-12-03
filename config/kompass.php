<?php

namespace Secondnetwork\Kompass;

return [

    'sets' => [
        'default' => [
            'fallback' => 'border-all',
        ],
    ],

    'available_locales' => [
        'en',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify attributes related to your application file system
    |
    */

    'storage' => [
        'disk' => env('FILESYSTEM_DRIVER', 'public'),
    ],

    /*
     |--------------------------------------------------------------------------
     | Kompass Route Middleware
     |--------------------------------------------------------------------------
     |
     | Here you may specify which middleware Kompass will assign to the routes
     | that it registers with the application. When necessary, you may modify
     | these middleware; however, this default value is usually sufficient.
     |
     */

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of Kompass's features are optional. You may disable the features
    | by removing them from this array. You're free to only remove some of
    | these features or you can even remove all of these if you need to.
    |
    */

    'features' => [
        Features::profilePhotos(),
        Features::accountDeletion(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Date Format
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default date format for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'dateformat' => 'd.m.Y H:i',

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports “GD Library” and “Imagick” to process images
    | internally. Depending on your PHP setup, you can choose one of them.
    |
    | Included options:
    |   - \Intervention\Image\Drivers\Gd\Driver::class
    |   - \Intervention\Image\Drivers\Imagick\Driver::class
    |
    */

    'driver' => \Intervention\Image\Drivers\Gd\Driver::class,


    // Qualitätseinstellungen
    'quality' => [
        'avif' => 50,
        'webp' => 80,
        'jpeg' => 85,
    ],

    // Standardgrößen für Konvertierung, falls nichts übergeben wird
    'default_width' => 1600,
    'default_height' => 1600,

    /*
    |--------------------------------------------------------------------------
    | Configuration Options
    |--------------------------------------------------------------------------
    |
    | These options control the behavior of Intervention Image.
    |
    | - "autoOrientation" controls whether an imported image should be
    |    automatically rotated according to any existing Exif data.
    |
    | - "decodeAnimation" decides whether a possibly animated image is
    |    decoded as such or whether the animation is discarded.
    |
    | - "blendingColor" Defines the default blending color.
    */

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'blendingColor' => 'ffffff',
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Photo Disk
    |--------------------------------------------------------------------------
    |
    | This configuration value determines the default disk that will be used
    | when storing profile photos for your application's users. Typically
    | this will be the "public" disk but you may adjust this if needed.
    |
    */

    'profile_photo_disk' => 'public',

    // Defines on which disk images, uploaded through the editor, should be stored.
    'default_img_upload_disk' => 'public',

    // Defines on which disk images, downloaded by pasting an image url into the editor, should be stored.
    'default_img_download_disk' => 'public',



    'prefix' => '',
];
