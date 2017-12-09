<?php

namespace App\Services\FileSystem;

use App\Services\FileSystem\Exceptions\FileOperationException;

class Concrete
{
    /**
     * @var \App\Services\FileSystem\FileAdapter
     */
    protected $fileAdapter;

    /**
     * @var string
     */
    protected $fullPath;

    /**
     * @var string[]
     */
    protected $pathInfo;

    public function __construct(
        string $src
    ) {
        $this->parsePath($src);
        $this->fileAdapter = new FileAdapter();
    }

    /**
     * @param string $dir
     * @param string $name
     * @param bool $saveExtension
     *
     * @return $this
     * @throws \App\Services\FileSystem\Exceptions\FileOperationException
     */
    public function saveAs(string $dir, string $name, bool $saveExtension = true)
    {
        $dist = $this->getDistPath($dir, $name, $saveExtension);
        $src = realpath($this->getFullPath());

        try {
            $res = copy($src, $dist);
        } catch (\Exception $e) {
            throw new FileOperationException("Cannot save file: " . $e->getMessage());
        }

        if ($res) {
            $this->parsePath($dist);

            return $this;
        }

        throw new FileOperationException("Cannot save file: Unknown error");
    }

    /**
     * @return string
     */
    public function getDirName()
    {
        return $this->pathInfo['dirname'];
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->pathInfo['filename'];
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->pathInfo['extension'];
    }

    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * @param string $path
     *
     * @return void
     */
    protected function parsePath(string $path)
    {
        $this->fullPath = $path;
        $this->pathInfo = pathinfo($path);
    }

    /**
     * @param string $dir
     * @param string $name
     * @param bool $saveExtension
     *
     * @return string
     */
    protected function getDistPath(string $dir, string $name, bool $saveExtension = true)
    {
        $dist = $dir . DIRECTORY_SEPARATOR . $name;

        if ($saveExtension) {
            $dist .= '.' . $this->getExtension();
        }

        return $dist;
    }

}
