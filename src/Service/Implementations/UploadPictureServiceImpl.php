<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Service\UploadPictureService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadPictureServiceImpl implements UploadPictureService
{
    private const DEFAULT_IMAGE = 'DefaultUser.png';
    private string $targetDirectory;
    private SluggerInterface $slugger;

    public function __construct(string $targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function uploadPicture(UploadedFile $uploadedPicture): string
    {
        $originalName = pathinfo($uploadedPicture->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalName);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $uploadedPicture->guessExtension();

        $uploadedPicture->move($this->targetDirectory, $fileName);

        return $fileName;
    }

    public function deletePicture(string $image): void
    {
        if (self::DEFAULT_IMAGE !== $image) {
            unlink($this->targetDirectory . '/' . $image);
        }
    }
}
