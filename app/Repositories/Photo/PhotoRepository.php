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

    /**
     * @param int $page
     * @param int $perPage
     * @param array $conditions
     *
     * @return \App\Models\Photo[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get(int $page = 1, int $perPage = 12, $conditions = [])
    {
        $offset = ($page - 1) * $perPage;

        return $this->model
            ->where($conditions)
            ->orderBy('id', 'DESC')
            ->limit($perPage)
            ->offset($offset)
            ->get();
    }

    /**
     * @param int $id
     *
     * @return \App\Models\Photo|null
     */
    public function findOne(int $id)
    {
        return $this->model->where(['id' => $id])->get()->first();
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

    public function updateOne(int $id, array $attributes)
    {
        $photo = $this->findOne($id);
        if (!$photo) {
            return false;
        }

        $fullPath = false;
        if (isset($attributes['full_path'])) {
            $fullPath = $attributes['full_path'];
            unset($attributes['full_path']);
        }
        $photo->setRawAttributes($attributes);

        if ($fullPath) {
            $photo->setFullPath($fullPath);
        }

        return $photo->save() ? $photo : false;
    }

}
