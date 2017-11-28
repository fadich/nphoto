<?php

namespace App\Repositories\Photo;

use App\Models\Photo;

class PhotoRepository
{
    /**
     * @var \App\Models\Photo
     */
    protected $model;

    public function __construct(
        Photo $photo
    ) {
        $this->model = new Photo();
    }

    public function get(int $page = 1, int $perPage = 12)
    {
        $offset = ($page - 1) * $perPage;

        return $this->model->orderBy('id', 'DESC')->limit($perPage)->offset($offset)->get();
    }

    public function create(array $attributes)
    {
        $fullPath = false;
        if (isset($attributes['full_path'])) {
            $fullPath = $attributes['full_path'];
            unset($attributes['full_path']);
        }

        $this->model->setRawAttributes($attributes);

        if ($fullPath) {
            $this->model->setFullPath($fullPath);
        }

        return $this->model->save() ? $this->model : false;
    }

}
