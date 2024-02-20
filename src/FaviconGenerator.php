<?php

namespace Secondnetwork\Kompass;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class FaviconGenerator
{
    protected string $distPath;

    public function __construct(
        protected string $filePath,
        protected string $publicPath = 'favicon',
    ) {
        if (! file_exists(public_path($this->publicPath))) {
            mkdir(public_path($this->publicPath), 0755, true);
        }
        $this->distPath = public_path($this->publicPath);
    }

    public function generateFaviconsFromImagePath()
    {
        $manager = new ImageManager(new Driver());
        // create an image manager instance with imagick driver
        // Image::configure(['driver' => 'imagick']);

        $image = $manager->read($this->filePath);

        $image->resize(192, 192)->save($this->distPath.'/android-chrome-192x192.png', 100, 'png');
        $image->resize(512, 512)->save($this->distPath.'/android-chrome-512x512.png', 100, 'png');
        $image->resize(180, 180)->save($this->distPath.'/apple-touch-icon.png', 100, 'png');
        $image->resize(16, 16)->save($this->distPath.'/favicon-16x16.png', 100, 'png');
        $image->resize(32, 32)->save($this->distPath.'/favicon-32x32.png', 100, 'png');
        $image->resize(32, 32)->save($this->distPath.'/favicon.ico', 100, 'ico');
        $image->resize(150, 150)->save($this->distPath.'/mstile-150x150.png', 100, 'png');

        // // favicon.ico
        // $icon = new \Imagick();
        // $icon->addImage(new \Imagick($this->distPath . "/favicon-16x16.png"));
        // $icon->addImage(new \Imagick($this->distPath . "/favicon-32x32.png"));
        // $icon->setResolution(16,16);
        // $icon->writeImages($this->distPath . "/favicon.ico", true);

        $this->saveBrowserConfigXml();
        $this->saveSiteWebManifest();
    }

    public function saveBrowserConfigXml(): void
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <browserconfig>
                    <msapplication>
                        <tile>
                            <square150x150logo src="/favicons/mstile-150x150.png"/>
                            <TileColor>#FFFFFF</TileColor>
                        </tile>
                    </msapplication>
                </browserconfig>';

        $xmlFile = fopen("{$this->distPath}/browserconfig.xml", 'w') or exit('Unable to open file!');
        fwrite($xmlFile, $xml);
        fclose($xmlFile);
    }

    public function saveSiteWebManifest(): void
    {
        $json = '{
                    "name": "",
                    "short_name": "",
                    "icons": [
                        {
                            "src": "/'.$this->publicPath.'/android-chrome-192x192.png",
                            "sizes": "192x192",
                            "type": "image/png"
                        },
                        {
                            "src": "/'.$this->publicPath.'/android-chrome-512x512.png",
                            "sizes": "512x512",
                            "type": "image/png"
                        }
                    ],
                    "theme_color": "#ffffff",
                    "background_color": "#ffffff",
                    "display": "standalone"
                }';

        $jsonFile = fopen("{$this->distPath}/site.webmanifest", 'w') or exit('Unable to open file!');
        fwrite($jsonFile, $json);
        fclose($jsonFile);
    }

    public static function generateHtmlMetaIcons(string $publicPath = 'favicons'): string
    {
        $html = '
            <link rel="apple-touch-icon" sizes="180x180" href=" '.$publicPath.'/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="32x32" href=" '.$publicPath.'/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href=" '.$publicPath.'/favicon-16x16.png">
            <link rel="manifest" href=" '.$publicPath.'/site.webmanifest">
            <link rel="mask-icon" href=" '.$publicPath.'/safari-pinned-tab.svg" color="#5bbad5">
            <meta name="msapplication-TileColor" content="#da532c">
            <meta name="theme-color" content="#ffffff">';

        return $html;
    }
}
