<?php

namespace App\Http\Controllers\Admin;

use App\Services\FileAdapter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhotosController extends Controller
{
    const UPDATED_PHOTOS_DIR = 'photos/originals';

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
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile[] $files */
        $files = $this->request->files->get('files');
        $saved = $this->fileAdapter->uploadMulti($files, static::UPDATED_PHOTOS_DIR, true);

        if ($saved) {
            session()->flash('message', 'Files has been saved');
        } else {
            session()->flash('message', $this->fileAdapter->lastError());
        }

        return $this->redirect(route('admin.index'));
    }
}
