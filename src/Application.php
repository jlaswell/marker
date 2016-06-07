<?php

namespace RealPage\Marker;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;

class Application
{
    public $config;

    public $filesystem;

    protected $configDirectoryPath;

    protected $configFilename;

    public function __construct(
        $configDirectoryPath = 'config',
        $configFilename = 'marker.php',
        FilesystemContract $filesystem = null
    ) {
        $this->configDirectoryPath = $configDirectoryPath;
        $this->configFilename      = $configFilename;
        $this->loadConfig();
        $this->filesystem = $filesystem ?? new Filesystem();
    }

    public function getConfigDirectoryPath(): string
    {
        return realpath($this->configDirectoryPath);
    }

    protected function getConfigFilePath($filename): string
    {
        return $this->getConfigDirectoryPath() . DIRECTORY_SEPARATOR . $filename;
    }

    protected function loadConfig()
    {
        $this->config = new ConfigRepository(require $this->getConfigFilePath($this->configFilename));
    }
}
