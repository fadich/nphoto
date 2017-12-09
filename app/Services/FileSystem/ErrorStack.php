<?php

namespace App\Services\FileSystem;

trait ErrorStack
{
    /**
     * @var string[]
     */
    protected $errors = [];

    /**
     * @return string|null
     */
    public function lastError()
    {
        return end($this->errors);
    }

}
