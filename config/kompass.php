<?php

namespace Secondnetwork\Kompass;

return [

    'sets' => [
        'default' => [
            'fallback' => 'border-all',
        ],
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
    | Profile Photo Disk
    |--------------------------------------------------------------------------
    |
    | This configuration value determines the default disk that will be used
    | when storing profile photos for your application's users. Typically
    | this will be the "public" disk but you may adjust this if needed.
    |
    */

    'profile_photo_disk' => 'public',

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
    | Media Manager
    |--------------------------------------------------------------------------
    |
    | Here you can specify if media manager can show hidden files like(.gitignore)
    |
    */

    'media' => [
        // The allowed mimetypes to be uploaded through the media-manager.
        'allowed_mimetypes' => '*', //All types can be uploaded

        /*'allowed_mimetypes' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/bmp',
        'video/mp4',
    ],*/

        //Path for media-manager. Relative to the filesystem.
        'path' => '/',
        'show_folders' => true,
        'allow_upload' => true,
        'allow_move' => true,
        'allow_delete' => true,
        'allow_create_folder' => true,
        'allow_rename' => true,

        'full' => [
            // 'width' => '2500',
            'quality' => 70,
        ],

        'upsize' => true,
        'thumbnails' => [
            [
                'type' => 'fit',
                'name' => 'small',
                'width' => 320,
                'height' => 220,

            ],
            [
                'type' => 'fit',
                'name' => 'medium',
                'width' => 720,
                'height' => 720,

            ],
            [
                'type' => 'fit',
                'name' => 'hd',
                'width' => 1920,
                'height' => 1080,

            ],
        ],
    ],

    'hidden_files' => false,

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
        'update-password-form' => Livewire\UpdatePasswordForm::class,
        'update-profile-photo' => Livewire\updateprofilephoto::class,
        'account' => Livewire\AccountForm::class,
        'roles' => Livewire\Roles::class,
        'eventdata' => Livewire\Eventdata::class,
        'editorjs' => Livewire\EditorJS::class,
        'redirect' => Livewire\Redirection::class,
    ],

    'prefix' => '',
];
