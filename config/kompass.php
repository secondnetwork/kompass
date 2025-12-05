<?php

namespace Secondnetwork\Kompass;

use Secondnetwork\Kompass\Features;

return [

    /*
    |--------------------------------------------------------------------------
    | Global Settings
    |--------------------------------------------------------------------------
    |
    | Basic configuration for Kompass sets and available locales.
    |
    */

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
    | Here you can specify attributes related to your application file system.
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
    | that it registers with the application.
    |
    */

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of Kompass's features are optional. You may disable the features
    | by removing them from this array.
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
    | default date format for your application.
    |
    */

    'dateformat' => 'd.m.Y H:i',

    /*
    |--------------------------------------------------------------------------
    | Image Processing Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for Intervention Image V3 and the Kompass ImageFactory.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images.
    |
    | Options:
    |   - \Intervention\Image\Drivers\Gd\Driver::class
    |   - \Intervention\Image\Drivers\Imagick\Driver::class
    |
    */

    'driver' => \Intervention\Image\Drivers\Gd\Driver::class,

    /*
    |--------------------------------------------------------------------------
    | Blur Placeholder (LQIP)
    |--------------------------------------------------------------------------
    |
    | If set to true, a tiny, blurred Base64 representation of the image will
    | be generated and embedded into the HTML. This is displayed while the
    | high-resolution image is loading (Low Quality Image Placeholder).
    |
    */

    'generate_blur_placeholder' => true,

    /*
    |--------------------------------------------------------------------------
    | Default Image Quality
    |--------------------------------------------------------------------------
    |
    | Global default quality settings (0-100) for different file formats.
    | These values are used if no specific quality is defined in a size preset.
    |
    */

    'quality' => [
        'avif' => 50,
        'webp' => 80,
        'jpeg' => 85,
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Presets (Sizes)
    |--------------------------------------------------------------------------
    |
    | Define named presets for your images here. You can reference these keys
    | (e.g., 'thumbnail', 'landscape') in your Blade directives.
    |
    | Methods:
    | - 'scale': Resizes the image preserving aspect ratio.
    | - 'cover': Crops the image to fit the dimensions (smart crop).
    | - 'resize': Stretches the image to fit exactly (ignoring aspect ratio).
    | - 'scaleDown': Like scale, but prevents upscaling small images.
    |
    */

    'sizes' => [
        'thumbnail' => [
            'width' => 520, 
            'height' => 520, 
            'method' => 'cover', // Crops to square
            'quality' => 60
        ],
        'blog_single' => [
            'width' => 1200, 
            'height' => null, // Auto height
            'method' => 'scale', 
            'quality' => 80
        ],
        'landscape' => [
            'width' => 1280, 
            'height' => 720, 
            'method' => 'cover', // Enforces 16:9 ratio via crop
            'quality' => 75
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Configuration
    |--------------------------------------------------------------------------
    |
    | These settings are used when:
    | 1. No size key is provided to the helper function.
    | 2. A size key is provided that does not exist in the 'sizes' array above.
    |
    */

    'fallback' => [
        'width' => 1600, 
        'height' => 1600, 
        'method' => 'scaleDown', // Only shrinks images, never enlarges them
        'quality' => 85
    ],

    /*
    |--------------------------------------------------------------------------
    | Intervention Configuration Options
    |--------------------------------------------------------------------------
    |
    | These options control the internal behavior of Intervention Image.
    |
    | - "autoOrientation": Automatically rotate based on Exif data.
    | - "decodeAnimation": Keep animations (GIF/WebP) or decode first frame only.
    | - "blendingColor": Default background color for transparent images.
    |
    */

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'blendingColor' => 'ffffff',
    ],

    /*
    |--------------------------------------------------------------------------
    | Disk Configurations
    |--------------------------------------------------------------------------
    |
    | Define which storage disks are used for different operations.
    |
    */

    'profile_photo_disk' => 'public',

    // Defines on which disk images, uploaded through the editor, should be stored.
    'default_img_upload_disk' => 'public',

    // Defines on which disk images, downloaded by pasting an image url into the editor, should be stored.
    'default_img_download_disk' => 'public',

    'prefix' => '',
];