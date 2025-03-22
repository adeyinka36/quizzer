<?php

namespace App\Interfaces;


interface UploadImageInterface
{
    /**
     * Upload image
     *
     * @param string $image
     * @return string|false
     */
    public function uploadImage(string $image): string|false;
}