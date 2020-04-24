<?php

declare(strict_types=1);

namespace Ntavelis\Dockposer\Filesystem;

use Ntavelis\Dockposer\Contracts\FilesystemInterface;
use Ntavelis\Dockposer\Exception\FileNotFoundException;
use Ntavelis\Dockposer\Exception\UnableToCreateDirectory;
use Ntavelis\Dockposer\Exception\UnableToPutContentsToFile;

class Filesystem implements FilesystemInterface
{
    /**
     * @var string
     */
    private $pathPrefix;

    public function __construct(string $pathPrefix)
    {
        $this->pathPrefix = rtrim($pathPrefix, '\\/');
    }

    /**
     * @throws UnableToPutContentsToFile
     */
    public function put(string $path, string $contents): void
    {
        $location = $this->applyPathPrefix($path);
        $result = @file_put_contents($location, $contents, LOCK_EX);
        if ($result === false) {
            throw new UnableToPutContentsToFile('Unable to update files ' . $location . ' content');
        }
    }

    /**
     * @throws UnableToCreateDirectory
     */
    public function createDir(string $path): void
    {
        $location = $this->applyPathPrefix($path);
        if (!is_dir($location)) {
            if (false === @mkdir($location, 0777, false)
                || false === is_dir($location)) {
                throw new UnableToCreateDirectory('Unable to create directory ' . $location);
            }
        }
    }

    /**
     * @throws FileNotFoundException
     */
    public function compileStub(string $absolutePathToStub): string
    {
        if (!file_exists($absolutePathToStub)) {
            throw new FileNotFoundException("Unable to locate file {$absolutePathToStub}, make sure you use absolute path to file.");
        }

        return file_get_contents($absolutePathToStub) ?? '';
    }

    public function fileExists(string $filePath): bool
    {
        $location = $this->applyPathPrefix($filePath);
        if (!file_exists($location)) {
            return false;
        }
        return true;
    }

    public function dirExists(string $dirPath): bool
    {
        $location = $this->applyPathPrefix($dirPath);
        if (!is_dir($location)) {
            return false;
        }
        return true;
    }

    private function applyPathPrefix(string $path): string
    {
        return $this->pathPrefix . DIRECTORY_SEPARATOR . ltrim($path, '\\/');
    }
}
