<?php

namespace Secondnetwork\Kompass\Commands;

use Illuminate\Console\Command;
use Secondnetwork\Kompass\FaviconGenerator;

class FaviconGeneratorCommand extends Command
{
    public $signature = 'kompass:favicon-generator';

    public $description = 'Generate favicons based on a source file';

    public function handle(): int
    {

        $favicon = new FaviconGenerator(public_path('favicon/favicon.png'));
        $favicon->generateFaviconsFromImagePath();
        $this->comment('Generate favicons success');

        return self::SUCCESS;
    }
}
