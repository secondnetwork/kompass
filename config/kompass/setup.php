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

    'livewire' => [
        'menu' => Livewire\Frontend\Menu::class,
        'pageview' => Livewire\Frontend\Pageview::class,
        'blogview' => Livewire\Frontend\Blogview::class,

        'adminmenu' => Livewire\Menu::class,
        'blocks.blocks-table' => Livewire\BlocksTable::class,
        'blocks.blocks-data' => Livewire\BlocksData::class,
        'pages.posts-table' => Livewire\PostsTable::class,
        'pages.posts-show' => Livewire\PostsData::class,
        'pages.pages-table' => Livewire\PagesTable::class,
        'pages.pages-show' => Livewire\PagesData::class,
        'menus.menus-table' => Livewire\MenuTable::class,
        'menus.menus-show' => Livewire\MenuData::class,
        'medialibrary' => Livewire\Medialibrary::class,
        'settings' => Livewire\Settings::class,
        'settings.page-information' => Livewire\Settings\PageInformation::class,
        'settings.backend' => Livewire\Settings\Backend::class,
   
        'setup.logo' => Livewire\Setup\Logo::class,
        'setup.background' => Livewire\Setup\Background::class,
        'setup.color' => Livewire\Setup\Color::class,
        'setup.favicon' => Livewire\Setup\Favicon::class,
        'setup.css' => Livewire\Setup\Css::class,
        'brokenlink' => Livewire\Brokenlink::class,
        'redirection' => Livewire\Redirection::class,
        'update-password-form' => Livewire\UpdatePasswordForm::class,
        'update-profile-photo' => Livewire\UpdateProfilePhoto::class,
        'account' => Livewire\AccountForm::class,
        'roles' => Livewire\Roles::class,
        'datafield-item' => Livewire\DatafieldItem::class,
        'editorjs' => Livewire\EditorJS::class,
        'redirect' => Livewire\Redirection::class,
    ],

    'prefix' => '',
];
