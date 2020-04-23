<?php

declare(strict_types=1);

namespace Ntavelis\Dockposer\Filesystem;

use League\Flysystem\FilesystemInterface as LeagueFilesystem;
use Ntavelis\Dockposer\Contracts\FilesystemInterface;

class Filesystem implements FilesystemInterface
{
    /**
     * @var LeagueFilesystem
     */
    private $implementation;

    public function __construct(LeagueFilesystem $implementation)
    {
        $this->implementation = $implementation;
    }

    public function put(string $path, string $contents, array $config = []): bool
    {
        return $this->implementation->put($path, $contents, $config);
    }

    public function createDir(string $dirname, array $config = []): bool
    {
        return $this->implementation->createDir($dirname, $config);
    }
}
