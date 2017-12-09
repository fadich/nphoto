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
 * @property string $miniaturePhoto
 * @property string $clientFilename
 * @property string $originalPath
 * @property string $originalPhoto
 * @property string $createdAt
 * @property string $updatedAt
 * @property string|integer $published
 */
class Photo extends Model
{
    use ModelAttributesTrait;

    protected $table = 'photo';

    protected $fillable = [
        'title',
        'description',
        'base_path',
        'filename',
        'miniature',
        'client_filename',
        'original_path',
        'published',
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
            'miniature'      => $this->miniaturePhoto,
            'clientFilename' => $this->clientFilename,
            'createdAt'      => $this->createdAt,
            'updatedAt'      => $this->updatedAt,
            'published'      => $this->published,
        ];
    }

    public function getOriginalPhoto()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $this->filename;
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
