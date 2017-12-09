<?php

namespace App\Services\FileSystem;

class FileUploader extends FileAdapter
{
    /**
     * @param \Illuminate\Http\File|\Illuminate\Http\UploadedFile[] $files
     * @param string $path
     *   Uploading path
     * @param bool $originalNames
     *
     * @return bool[]|string[]
     */
    public function uploadMulti(array $files, string $path, bool $originalNames = false)
    {
        $results = [];

        foreach ($files as $key => $file) {
            $results[] = $this->upload($file, $path, $originalNames);
        }

        return $results;
    }

    /**
     * @param \Illuminate\Http\File|\Illuminate\Http\UploadedFile $file
     * @param string $path
     *   Uploading path
     * @param bool $originalName
     *
     * @return string|false
     */
    public function upload($file, string $path, bool $originalName = false)
    {
        $name = null;

        $randomName = function () use ($file) {
            return $this->generateFilename(null, '.' . $file->getClientOriginalExtension());
        };
        $name = $originalName ? $file->getClientOriginalName() : $randomName();

        if (count($this->checkFiles($name, $path))) {
            $this->errors[] = 'File ' . $file->getClientOriginalName() . ' cannot be saved due to filename conflicts';
            return false;
        }

        return $this->adapter->putFileAs($path, $file, $name);
    }

}
