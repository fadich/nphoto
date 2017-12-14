<?php

namespace App\Services\FileSystem;

use Illuminate\Support\Facades\Storage;

class FileAdapter
{
    use ErrorStack;

    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter $adapter
     */
    public $adapter;

    public function __construct()
    {
        $this->adapter = Storage::disk('local');
    }

    /**
     * @param string|array|\SplFileInfo[] $name
     *   File name / names of files.
     * @param string $path
     *
     * @return string[]
     *   Names of existed files
     */
    public function checkFiles($name, string $path)
    {
        $exists = [];
        $files = is_array($name) ? $name : [$name];

        foreach ($files as $file) {
            $name = is_string($file) ? $file : $file->getFilename();
            $fileName = $path . DIRECTORY_SEPARATOR . $name;
            $fullPath = $this->adapter->path($fileName);

            if (is_file($fullPath)) {
                $exists[] = $file;
            }
        }

        return $exists;
    }

    /**
     * @param \SplFileInfo|null $file
     * @param string $suffix
     *
     * @return string
     */
    public function generateFilename(\SplFileInfo $file = null, string $suffix = '')
    {
        $name = round(microtime(true) * 1000);

        if ($file) {
            $ext = $file->getExtension();
            $ext = $ext ?: substr($file->getFilename(), strrpos($file->getFilename(), '.') + 1);
            if ($ext) {
                $name .= '.' . $ext;
            }
        }

        return $name . $suffix;
    }

    /**
     * @param string $src
     * @param string $dist
     *
     * @return bool
     */
    public function move(string $src, string $dist)
    {
        return $this->adapter->move($src, $dist);
    }

    /**
     * @param string $src
     * @param string $dist
     *
     * @return bool
     */
    public function copy(string $src, string $dist)
    {
        return $this->adapter->copy($src, $dist);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function delete(string $path)
    {
        return $this->adapter->delete($path);
    }

}
