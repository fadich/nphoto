<?php

namespace App\Models;

use App\Traits\ModelAttributesTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Photo
 * @package App\Models
 *
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $basePath
 * @property string $filename
 * @property string $clientFilename
 * @property string $originalPath
 * @property string $fullPath
 * @property string $createdAt
 * @property string $updatedAt
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
        'client_filename',
        'original_path',
    ];

    public function toArray(bool $full = false)
    {
        if ($full) {
            return parent::toArray();
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'fullPath' => $this->fullPath,
            'clientFilename' => $this->clientFilename,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

    public function getFullPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . $this->filename;
    }

    public function setFullPath(string $path)
    {
        $path = explode(DIRECTORY_SEPARATOR, $path);
        $this->setAttribute('filename', array_pop($path));
        $this->setAttribute('base_path', implode(DIRECTORY_SEPARATOR, $path));

        return $this;
    }

    public function getCreatedAt()
    {
        /** @var \Illuminate\Support\Carbon $time */
        $time = $this->getAttribute('created_at');

        return $time->getTimestamp();
    }

    public function getUpdatedAt()
    {
        /** @var \Illuminate\Support\Carbon $time */
        $time = $this->getAttribute('updated_at');

        return $time->getTimestamp();
    }

}
