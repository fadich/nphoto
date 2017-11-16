<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileAdapter
{
    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter $adapter
     */
    public $adapter;

    /**
     * @var string[]
     */
    protected $errors = [];

    public function __construct()
    {
        $this->adapter = Storage::disk('local');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile[] $files
     * @param string $path
     *   Uploading path
     * @param bool $originalName
     *
     * @return bool
     */
    public function uploadMulti(array $files, string $path, bool $originalName = false)
    {
        $names = [];

        foreach ($files as $key => $file) {
            $randomName = function () use ($file) {
                return $this->generateFilename(null, '.' . $file->getClientOriginalExtension());
            };
            $names[$key] = $originalName ? $file->getClientOriginalName() : $randomName();
        }

        if (count($this->checkFiles($names, $path))) {
            $this->errors[] = 'Files cannot be saved due to filename conflicts';
            return false;
        }

        foreach ($files as $key => $file) {
            $this->adapter->putFileAs($path, $file, $names[$key]);
        }

        return true;
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
     * @return string|null
     */
    public function lastError()
    {
        return end($this->errors);
    }
}
