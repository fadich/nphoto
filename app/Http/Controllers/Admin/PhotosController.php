<?php

namespace App\Http\Controllers\Admin;

use App\Services\FileAdapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PhotosController extends Controller
{
    const UPDATED_PHOTOS_DIR = 'public/photos/originals';

    /**
     * @var \App\Services\FileAdapter
     */
    protected $fileAdapter;

    public function __construct(
        Request $request,
        FileAdapter $fileAdapter
    ) {
        parent::__construct($request);

        $this->fileAdapter = $fileAdapter;
    }

    public function createAction()
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($this->request->all(), [
            'files' => 'required|array',
            'files.*' => 'required|image',
        ]);

        $validator->validate();

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile[] $files */
        $files = $this->request->files->get('files') ?: [];
        $results = $this->fileAdapter->uploadMulti($files, static::UPDATED_PHOTOS_DIR);
        $success = 0;
        $errors = [];

        foreach ($results as $key => $result) {
            if ($result) {
                // Replace for frontend...
                $results[$key] = str_replace('public', 'storage', $result);
                $success++;
                continue;
            }

            $file = $files[$key];
            $errors[] = $file->getClientOriginalName();
        }

        if ($success === 0) {
            // Error of each upload...
            return $this->json([
                'success' => false,
                'files' => $results,
                'message' => 'Files has not been saved',
            ], 503);
        } elseif (count($results) === $success) {
            // Success of each files...
            return $this->json([
                'success' => true,
                'files' => $results,
                'message' => 'Files has been saved successfully',
            ]);
        }
        // Errors uploading of some files...
        $message = 'Files has not been saved: <ul>' . implode('', array_map(function ($name) {
            return '<li>' . $name . '</li>';
        }, $errors)) . '</ul>';

        return $this->json([
            'success' => false,
            'files' => $results,
            'message' => $message,
        ], 503);
    }
}
