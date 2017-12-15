<?php

namespace App\Models;

use App\Traits\ModelAttributesTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Photo
 * @package App\Models
 *
 * @property string|integer $id
 * @property string $title
 * @property string $description
 * @property string $basePath
 * @property string $filename
 * @property string $largePhoto
 * @property string $mediumPhoto
 * @property string $smallPhoto
 * @property string $miniaturePhoto
 * @property string $clientFilename
 * @property string $originalPath
 * @property string $originalPhoto
 * @property string|integer $status
 * @property string $createdAt
 * @property string $updatedAt
 */
class Photo extends Model
{
    use ModelAttributesTrait;

    const STATUS_PUBLISHED = 10;
    const STATUS_UNPUBLISHED = 5;
    const STATUS_DELETED = 0;

    protected $table = 'photo';

    protected $fillable = [
        'title',
        'description',
        'base_path',
        'filename',
        'large',
        'medium',
        'small',
        'miniature',
        'status',
        'client_filename',
        'original_path',
    ];

    public function toArray(bool $full = false)
    {
        if ($full) {
            return parent::toArray();
        }

        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'description'    => $this->description,
            'original'       => $this->originalPhoto,
            'large'          => $this->largePhoto,
            'medium'         => $this->mediumPhoto,
            'small'          => $this->smallPhoto,
            'miniature'      => $this->miniaturePhoto,
            'clientFilename' => $this->clientFilename,
            'status'         => $this->status,
            'createdAt'      => $this->createdAt,
            'updatedAt'      => $this->updatedAt,
        ];
    }

    public function getOriginalPhoto()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $this->filename;
    }

    public function getLargePhoto()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $this->large;
    }

    public function getMediumPhoto()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $this->medium;
    }

    public function getSmallPhoto()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $this->small;
    }

    public function getMiniaturePhoto()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $this->miniature;
    }

    public function getCreatedAt()
    {
        /** @var \Illuminate\Support\Carbon $time */
        $time = $this->getAttribute('created_at');

        return $time ? $time->getTimestamp() : 0;
    }

    public function getUpdatedAt()
    {
        /** @var \Illuminate\Support\Carbon $time */
        $time = $this->getAttribute('updated_at');

        return $time ? $time->getTimestamp() : 0;
    }

}
