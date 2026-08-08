<?php

namespace Secondnetwork\Kompass\Features;

use Illuminate\Support\Facades\Image;

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
        $image = Image::fromPath($this->filePath)->toPng();

        file_put_contents($this->distPath.'/android-chrome-512x512.png', $image->resize(512, 512)->toBytes());
        file_put_contents($this->distPath.'/android-chrome-192x192.png', $image->resize(192, 192)->toBytes());
        file_put_contents($this->distPath.'/apple-touch-icon.png', $image->resize(192, 192)->toBytes());
        file_put_contents($this->distPath.'/mstile-150x150.png', $image->resize(150, 150)->toBytes());
        file_put_contents($this->distPath.'/favicon-32x32.png', $image->resize(32, 32)->toBytes());
        file_put_contents($this->distPath.'/favicon.png', $image->resize(32, 32)->toBytes());
        file_put_contents($this->distPath.'/favicon-16x16.png', $image->resize(16, 16)->toBytes());

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
