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
        $avifImagickSupport = '0';
        if (extension_loaded('imagick') && class_exists('Imagick')) {
            $imagick = new \Imagick();
            $formats = $imagick->queryFormats();
            if (in_array('AVIF', $formats)) {
                $avifImagickSupport = '1';
            } else {
                $avifImagickSupport = '0';
            }
        }

        if (! function_exists('imageavif') && $avifImagickSupport <= '0') {
            $this->error('no support for AVIF on the Server');
        } else {

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

                    self::convert(asset($assetUrl), $des, 60, 6, $thumbnail, $avifImagickSupport);
                }
            }
            $this->info('Thumbnails generated successfully!');
        }

    }

    public static function convert($src, $des, $quality, $speed, $thumbnailavif, $avifImagickSupport)
    {
        if (! function_exists('imageavif') && $avifImagickSupport <= '0') {
            return;
        }

        if (! $src && ! $des && ! $quality && ! $speed) {
            return false;
        }

        if ($avifImagickSupport > '0') {
            $imagick = new \Imagick();
            $imagick->readImage($src);
            $imagick->setImageFormat('avif');
            if ($quality > 0) {
                $imagick->setCompressionQuality($quality);
                $imagick->setImageCompressionQuality($quality);
            } else {
                $imagick->setCompressionQuality(1);
                $imagick->setImageCompressionQuality(1);
            }

            $imagick->writeImage($des);
            $imagick->scaleImage(400, 0);
            $imagick->writeImage($thumbnailavif);

            $imagick->destroy();

            return;
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
        @imageavif($sourceGDImg, $des, $quality, $speed);
        @imagedestroy($sourceGDImg);
    }
}
