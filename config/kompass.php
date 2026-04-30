<?php

namespace Secondnetwork\Kompass;

use Intervention\Image\Drivers\Gd\Driver;
use Secondnetwork\Kompass\Models\Block;
use Secondnetwork\Kompass\Models\Datafield;
use Secondnetwork\Kompass\Models\File;
use Secondnetwork\Kompass\Models\Meta;

return [

    /*
    |--------------------------------------------------------------------------
    | Essential Settings
    |--------------------------------------------------------------------------
    |
    | Core configuration required for Kompass to function properly.
    |
    */

    'middleware' => ['web'],

    'storage' => [
        'disk' => env('FILESYSTEM_DRIVER', 'public'),
    ],

    'meta' => [
        'morph_type' => 'integer',
    ],

    'serializable_classes' => [
        Block::class,
        Datafield::class,
        Meta::class,
        File::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Optional features that can be enabled or disabled.
    |
    */

    'features' => [
        Features::profilePhotos(),
        Features::accountDeletion(),
        Features::activityLog(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    |
    | Available locales and date formatting for the application.
    |
    */

    'available_locales' => [
        'de',
        'en',
        'bg',
        'cs',
        'da',
        'el',
        'es',
        'et',
        'fi',
        'fr',
        'ga',
        'hr',
        'hu',
        'it',
        'lt',
        'lv',
        'mt',
        'nl',
        'pl',
        'pt',
        'ro',
        'sk',
        'sl',
        'sv',
        'tr',
        'no',
        'is',
        'sr',
        'bs',
        'sq',
        'mk',
        'ru',
        'uk',
    ],

    'dateformat' => 'd.m.Y H:i',

    /*
    |--------------------------------------------------------------------------
    | Image Processing
    |--------------------------------------------------------------------------
    |
    | Settings for Intervention Image V3 and image handling.
    |
    */

    'driver' => Driver::class,

    'generate_blur_placeholder' => true,

    'quality' => [
        'avif' => 50,
        'webp' => 80,
        'jpeg' => 85,
    ],

    'sizes' => [
        'thumbnail' => [
            'width' => 520,
            'height' => null,
            'method' => 'scale',
            'quality' => 60,
        ],
        'blog_single' => [
            'width' => 1200,
            'height' => null,
            'method' => 'scale',
            'quality' => 80,
        ],
        'landscape' => [
            'width' => 1280,
            'height' => 720,
            'method' => 'cover',
            'quality' => 75,
        ],
    ],

    'fallback' => [
        'width' => 2500,
        'height' => 2500,
        'method' => 'scaleDown',
        'quality' => 85,
    ],

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'blendingColor' => 'ffffff',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage Disks
    |--------------------------------------------------------------------------
    |
    | Define which storage disks are used for different operations.
    |
    */

    'profile_photo_disk' => 'public',
    'default_img_upload_disk' => 'public',
    'default_img_download_disk' => 'public',

    'prefix' => '',

    'theme' => env('KOMPASS_THEME', null),

    /*
    |--------------------------------------------------------------------------
    | Sets (Legacy)
    |--------------------------------------------------------------------------
    |
    | Legacy configuration for sets. Consider migrating to newer config.
    |
    */

    'sets' => [
        'default' => [
            'fallback' => 'border-all',
        ],
    ],

];
