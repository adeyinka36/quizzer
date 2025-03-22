<?php

namespace App\Services;


use App\Interfaces\UploadImageInterface;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadImageToS3 implements UploadImageInterface
{
    public function uploadImage( $image): false|string
    {
        try{
            return Storage::disk('s3')->putFile('avatars', $image, [
                'visibility' => 'public',
            ]);
        }catch (\Exception $e){
            Log::info($e->getMessage());
            return false;
        }
    }
}