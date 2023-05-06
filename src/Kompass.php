<?php

namespace Secondnetwork\Kompass;

use Illuminate\Filesystem\Filesystem;

class Kompass
{
    protected $version;

    protected $filesystem;

    public $setting_cache = null;

    public $app;

    public function __construct()
    {
        $this->filesystem = app(Filesystem::class);

        $this->findVersion();
    }

    public function getVersion()
    {
        return $this->version;
    }

    protected function findVersion()
    {
        if (! is_null($this->version)) {
            return;
        }

        if ($this->filesystem->exists(base_path('composer.lock'))) {
            // Get the composer.lock file
            $file = json_decode(
                $this->filesystem->get(base_path('composer.lock'))
            );

            // Loop through all the packages and get the version
            foreach ($file->packages as $package) {
                if ($package->name == 'secondnetwork/kompass') {
                    $this->version = $package->version;
                    break;
                }
            }
        }
    }

    public function routes()
    {
        require __DIR__.'/../routes/web.php';
    }

    public function api()
    {
        require __DIR__.'/../routes/api.php';
    }
}
