<?php

namespace App\Services\FileSystem;


class Image extends Concrete
{
    /**
     * @var \Imagick
     */
    protected $image;

    public function __construct(
        string $src
    ) {
        parent::__construct($src);
        $this->image = new \Imagick($src);
    }

    /**
     * Resize image.
     *
     * @param int $width
     *
     * @return string
     *   ImageBlob.
     */
    public function resize(int $width)
    {
        $proportion = $width / $this->image->getImageWidth();
        $height = floor($this->image->getImageHeight() * $proportion);
        $width = floor($this->image->getImageWidth() * $proportion);

        $this->image->resizeImage($width, $height, \Imagick::FILTER_LANCZOS,1);

        return $this->image->writeImage($this->getFullPath());
    }

}
