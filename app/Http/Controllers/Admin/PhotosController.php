<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Photo\PhotoRepository;
use App\Services\FileAdapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PhotosController extends Controller
{
    const UPDATED_PHOTOS_DIR = 'public/photos/originals';

    /**
     * @var \App\Repositories\Photo\PhotoRepository
     */
    protected $photoRepository;

    /**
     * @var \App\Services\FileAdapter
     */
    protected $fileAdapter;

    public function __construct(
        Request $request,
        FileAdapter $fileAdapter,
        PhotoRepository $photoRepository
    ) {
        parent::__construct($request);

        $this->fileAdapter = $fileAdapter;
        $this->photoRepository = $photoRepository;
    }

    public function createAction()
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($this->request->all(), [
            'photos' => 'required|array',
            'photos.*' => 'required|image',
        ]);

        $validator->validate();

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile[] $files */
        $files = $this->request->files->get('photos') ?: [];
        $results = $this->fileAdapter->uploadMulti($files, static::UPDATED_PHOTOS_DIR);
        $success = 0;
        $errors = [];

        foreach ($results as $key => $result) {
            $file = $files[$key];

            if (!$result) {
                $errors[] = $file->getClientOriginalName();
                continue;
            }
            // Replace for frontend...
            $path = str_replace('public', 'storage', $result);

            try {
                $photo = $this->photoRepository->create([
                    'full_path' => $path,
                    'client_filename' => $file->getClientOriginalName(),
                    'original_path' => $this->fileAdapter->adapter->path($result),
                ]);
            } catch (\Exception $e) {
                $errors[] = $file->getClientOriginalName();
                $this->fileAdapter->delete($result);
                continue;
            }

            $results[$key] = $photo->toArray();

            $success++;
        }

        if ($success === 0) {
            // Error of each upload...
            return $this->json([
                'success' => false,
                'errors' => $errors,
                'message' => 'Files has not been saved',
            ], 503);
        } elseif (count($results) === $success) {
            // Success of each files...
            return $this->json([
                'success' => true,
                'photos' => $results,
                'message' => 'Photos has been saved successfully',
            ]);
        }
        // Errors uploading of some photos...
        $message = 'Photos has not been saved: <ul>' . implode('', array_map(function ($name) {
            return '<li>' . $name . '</li>';
        }, $errors)) . '</ul>';

        return $this->json([
            'success' => false,
            'photos' => $results,
            'errors' => $errors,
            'message' => $message,
        ], 503);
    }
}
