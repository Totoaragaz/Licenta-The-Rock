<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadPictureService
{
    public function uploadPicture(UploadedFile $uploadedPicture): string;

    public function deletePicture(string $image): void;
}
