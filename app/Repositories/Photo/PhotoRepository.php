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

    public function create(array $attributes)
    {
        if ($attributes['full_path']) {
            $path = $attributes['full_path'];
            $attributes['filename'] = substr($path, strrpos($path, '-') + 1);
            $attributes['base_path'] = base_path($path);
        }

        $this->model->setRawAttributes($attributes);

        return $this->model->save() ? $this->model : false;
    }

}
