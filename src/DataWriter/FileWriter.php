<?php

namespace Secondnetwork\Kompass\DataWriter;

use Illuminate\Filesystem\Filesystem;

class FileWriter
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The default configuration path.
     *
     * @var string
     */
    protected $defaultPath;

    protected $rewriter;

    /**
     * Create a new file configuration loader.
     *
     * @return void
     */
    public function __construct(Filesystem $files, string $defaultPath)
    {
        $this->files = $files;
        $this->defaultPath = $defaultPath;
        $this->rewriter = new Rewrite;
    }

    /**
     * Write an item value in a file.
     *
     * @param  mixed  $value
     */
    public function write(string $item, $value, string $filename, string $fileExtension = '.php'): bool
    {
        $path = $filename;

        $contents = $this->files->get($path);
        $contents = $this->rewriter->toContent($contents, [$item => $value]);

        return ! ($this->files->put($path, $contents) === false);
    }

    private function getPath(string $item, string $filename, string $ext = '.php'): ?string
    {
        $file = "{$this->defaultPath}/{$filename}{$ext}";

        dd($file);
        if ($this->files->exists($file) && $this->hasKey($file, $item)) {
            return $file;
        }

        return null;
    }

    private function hasKey(string $path, string $key): bool
    {
        $contents = file_get_contents($path);
        $vars = eval('?>'.$contents);

        $keys = explode('.', $key);

        $isset = false;
        while ($key = array_shift($keys)) {
            $isset = isset($vars[$key]);
            if (is_array($vars[$key])) {
                $vars = $vars[$key];
            }
        }

        return $isset;
    }
}
