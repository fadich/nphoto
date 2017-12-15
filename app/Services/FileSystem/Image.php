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
     * @param bool $soft
     *   Do not stretch the image larger than its original size.
     *
     * @return string ImageBlob.
     * ImageBlob.
     */
    public function resize(int $width, bool $soft = false)
    {
        if ($soft && $width > $this->image->getImageWidth()) {
            $width = $this->image->getImageWidth();
        }

        $proportion = $width / $this->image->getImageWidth();
        $height = floor($this->image->getImageHeight() * $proportion);
        $width = floor($this->image->getImageWidth() * $proportion);

        $this->image->resizeImage($width, $height, \Imagick::FILTER_LANCZOS,1);

        return $this->image->writeImage($this->getFullPath());
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->image->getImageHeight();
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->image->getImageWidth();
    }

}
