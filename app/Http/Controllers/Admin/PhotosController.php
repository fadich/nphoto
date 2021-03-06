<?php

namespace App\Http\Controllers\Admin;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Services\FileSystem\Image;
use App\Http\Controllers\Controller;
use App\Services\FileSystem\FileUploader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Photo\PhotoRepository;

class PhotosController extends Controller
{
    const UPDATED_PHOTOS_DIR = 'public/photos/originals';

    /**
     * @var \App\Repositories\Photo\PhotoRepository
     */
    protected $photoRepository;

    /**
     * @var \App\Services\FileSystem\FileUploader
     */
    protected $fileUploader;

    public function __construct(
        Request $request,
        FileUploader $fileUploader,
        PhotoRepository $photoRepository
    ) {
        parent::__construct($request);

        $this->fileUploader = $fileUploader;
        $this->photoRepository = $photoRepository;
    }

    public function listAction()
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($this->request->all(), [
            'page' => 'integer|min:1',
            'perPage' => 'integer|min:3|max:24',
        ]);
        $validator->validate();

        $page = $this->request->get('page') ?: 1;
        $perPage = $this->request->get('perPage') ?: 12;

        $offset = ($page - 1) * $perPage;

        $photos = (new Photo())
            ->where('status', '>', Photo::STATUS_DELETED)
            ->orderBy('id', 'DESC')
            ->limit($perPage)
            ->offset($offset)
            ->get();

        return $this->json([
            'photos' => $photos,
        ]);
    }

    public function createAction()
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($this->request->all(), [
            'photos' => 'required|array',
            'photos.*' => 'required|image|max:33554432',
        ]);

        $validator->validate();

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile[] $files */
        $files = $this->request->files->get('photos') ?: [];
        $results = $this->fileUploader->uploadMulti($files, static::UPDATED_PHOTOS_DIR);
        $success = 0;
        $errors = [];

        foreach ($results as $key => $result) {
            $file = $files[$key];

            if (!$result) {
                $errors[] = $file->getClientOriginalName();
                continue;
            }

            $large = new Image(Storage::path($result));
            $large->saveAs($large->getDirName(), $large->getFileName() . '_lg');
            $large->resize(1024, true);

            $medium = new Image(Storage::path($result));
            $medium->saveAs($medium->getDirName(), $medium->getFileName() . '_md');
            $medium->resize(640, true);

            $small = new Image(Storage::path($result));
            $small->saveAs($small->getDirName(), $small->getFileName() . '_sm');
            $small->resize(400, true);

            $mini = new Image(Storage::path($result));
            $mini->saveAs($mini->getDirName(), $mini->getFileName() . '_mn');
            $mini->resize(160, true);

            // Replace for frontend...
            $originalPath = str_replace('public', 'storage', $result);

            try {
                $photo = $this->photoRepository->create([
                    'filename' => basename($originalPath),
                    'large' => basename($large->getFullPath()),
                    'medium' => basename($medium->getFullPath()),
                    'small' => basename($small->getFullPath()),
                    'miniature' => basename($mini->getFullPath()),
                    'base_path' => dirname($originalPath),
                    'client_filename' => $file->getClientOriginalName(),
                    'original_path' => dirname($result),
                ]);
            } catch (\Exception $e) {
                $errors[] = $file->getClientOriginalName();
                $this->fileUploader->delete($result);
                if (app('env') === 'local') {
                    throw $e;
                }
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

    public function updateAction(int $id)
    {
        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($this->request->all(), [
            'title' => 'string|nullable',
            'description' => 'string|nullable',
            'status' => 'integer|min:0|max:10|nullable',
        ]);
        $validator->validate();

        $photo = $this->photoRepository->updateOne((int) $id, $this->request->all());

        if (!$photo) {
            return $this->json([
                'success' => false,
                'message' => 'Photos not found',
            ], 404);
        }

        return $this->json([
            'success' => true,
            'photos' => $photo->toArray(),
            'message' => 'Photo has been saved successfully',
        ]);
    }

    public function deleteAction(int $id)
    {
        $photo = $this->photoRepository->findOne((int) $id);

        if (!$photo) {
            return $this->json([
                'success' => false,
                'message' => 'Photos not found',
            ], 404);
        }

        $res = (bool)$photo->delete();

        $message = 'Cannot delete photo';
        if ($res) {
            $message = 'Photo has been saved successfully';
        }

        return $this->json([
            'success' => $res,
            'message' => $message,
        ]);
    }

}
