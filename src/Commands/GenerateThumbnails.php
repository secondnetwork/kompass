<?php

namespace Secondnetwork\Kompass\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Secondnetwork\Kompass\Models\File;

class GenerateThumbnails extends Command
{
    protected $signature = 'kompass:thumbnails:generate';

    protected $description = 'Generate thumbnails for all images in storage';

    public function handle(): void
    {

        $filesystem = config('kompass.storage.disk');
        $files = File::all();
        // $content = Storage::disk($this->filesystem)->get($path);
        $imageMimeTypes = [
            'jpeg',
            'jpg',
            'png',
            'webp',
            'gif',
        ];
        foreach ($files as $file) {
            if (in_array($file->extension, $imageMimeTypes)) {

                if ($file->path) {
                    $assetUrl = Storage::url($file->path.'/'.$file->slug.'.'.$file->extension);
                } else {
                    $assetUrl = Storage::url($file->slug.'.'.$file->extension);
                }

                if ($file->path) {
                    $des = Storage::path('public/'.$file->path.'/'.$file->slug.'.avif');
                    $thumbnail = Storage::path('public/'.$file->path.'/'.$file->slug.'_thumbnail.avif');
                } else {
                    $des = Storage::path('public/'.$file->slug.'.avif');
                    $thumbnail = Storage::path('public/'.$file->slug.'_thumbnail.avif');
                }

                self::convert(asset($assetUrl), $des, 60, 6, $thumbnail);
            }
        }
        $this->info('Thumbnails generated successfully!');
    }

    public static function convert($src, $des, $quality, $speed, $thumbnailavif)
    {
        if (! function_exists('imageavif')) {
            return;
        }
        if (! $src && ! $des && ! $quality && ! $speed) {
            return false;
        }

        $fileType = getimagesize($src)['mime'];

        if ($fileType == 'image/jpeg' || $fileType == 'image/jpg') {
            $sourceGDImg = @imagecreatefromjpeg($src);
        }
        if ($fileType == 'image/png') {
            $sourceGDImg = @imagecreatefrompng($src);
        }
        if ($fileType == 'image/webp') {
            $sourceGDImg = @imagecreatefromwebp($src);
        }
        if (gettype($sourceGDImg) == 'boolean') {
            return;
        }
        if ($thumbnailavif) {
            $width = imagesx($sourceGDImg);
            $height = imagesy($sourceGDImg);
            // Create a new blank image with different dimensions
            $newWidth = 400;
            $newHeight = ($height / $width) * $newWidth;
            $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
            // Resize the image
            imagecopyresized($thumbnail, $sourceGDImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            // @imagejpeg($thumbnail,$medium);
            @imageavif($thumbnail, $thumbnailavif, $quality, $speed);
        } else {
            @imageavif($sourceGDImg, $des, $quality, $speed);
        }

        @imagedestroy($sourceGDImg);
    }
}
