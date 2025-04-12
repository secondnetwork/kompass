<?php

namespace Secondnetwork\Kompass\DataWriter;

use Exception;
use Illuminate\Config\Repository as RepositoryBase;

class Repository extends RepositoryBase
{
    protected $writer;

    public function __construct(FileWriter $writer, array $items = [])
    {
        parent::__construct($items);
        $this->writer = $writer;
    }

    /**
     * Write a given configuration value to file.
     *
     * @param  mixed  $value
     */
    public function write(string $key, $value): bool
    {
        [$filename, $item] = $this->getFileAndReturnConfigProperty($key);

        $result = $this->writer->write($item, $value, $filename);

        if (! $result) {
            throw new Exception('File could not be written to');
        }

        $this->set($key, $value);

        return $result;
    }

    private function getFileAndReturnConfigProperty(string $key): array
    {
        $pathParts = explode('.', $key);
        $path = base_path('config');
        $foundFile = null;
        $remainingKey = '';

        for ($i = 0; $i < count($pathParts); $i++) {
            $path .= '/'.$pathParts[$i];
            $testPath = $path.'.php';
            if (file_exists($testPath)) {
                $foundFile = $testPath;
                $remainingKey = implode('.', array_slice($pathParts, $i + 1));
                break;
            }
        }

        if (! $foundFile) {
            throw new Exception("Configuration file for '{$key}' not found.");
        }

        return [$foundFile, $remainingKey];
    }

    /**
     * Split key into 2 parts. The first part will be the filename
     */
    private function parseKey(string $key): array
    {
        return preg_split('/\./', $key, 2);
    }
}
