<?php

namespace Secondnetwork\Kompass\Features;

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
        $manager = new ImageManager(new Driver);
        // create an image manager instance with imagick driver
        // Image::configure(['driver' => 'imagick']);

        $image = $manager->read($this->filePath);
        $image->resize(512, 512)->save($this->distPath.'/android-chrome-512x512.png', 100, 'png');
        $image->resize(192, 192)->save($this->distPath.'/android-chrome-192x192.png', 100, 'png');
        $image->resize(192, 192)->save($this->distPath.'/apple-touch-icon.png', 100, 'png');
        $image->resize(150, 150)->save($this->distPath.'/mstile-150x150.png', 100, 'png');
        $image->resize(32, 32)->save($this->distPath.'/favicon-32x32.png', 100, 'png');
        $image->resize(32, 32)->save($this->distPath.'/favicon.ico', 100, 'ico');
        $image->resize(16, 16)->save($this->distPath.'/favicon-16x16.png', 100, 'png');

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
}
