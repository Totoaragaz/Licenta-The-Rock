<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Entity\Thread;
use App\Repository\ThreadRepository;
use App\Transformer\ThreadTransformer;

class ThreadService
{
    public function __construct(
        private ThreadRepository $threadRepository,
        private ThreadTransformer $transformer,
        private UploadPictureServiceImpl $uploadPictureServiceImpl
    )
    {
    }

    public function getAllThreads(string $username, int $page): array
    {
        $threads = $this->threadRepository->getAllThreadsWithPage($username, $page);
        if ($threads) {
            $threadDTOs = [];
            foreach ($threads as $thread) {
                $threadDTOs[] = $this->transformer->transformThreadIntoDto($thread);
            }

            return $threadDTOs;
        }

        return [];
    }

    public function getAllThreadsNumberOfPages(string $username): int
    {
        $threads = $this->threadRepository->getAllThreads($username);
        return intdiv(sizeof($threads), 10) - (sizeof($threads) % 10 === 0) + 1;
    }

    public function createThread(Thread $thread): bool
    {
        $thread->setUploadDate(date_create());
        return $this->threadRepository->createThread($thread);
    }

    public function setThreadContent(array $text, array $files): array
    {
        $content = [];
        $images = $this->handleImages($files);
        for ($i = 0; $i < sizeof($text); $i++) {
            if ($text[$i] != null) {
                $content[] = $text[$i];
            }
            if ($i < sizeof($images)) {
                $content[] = $images[$i];
            }
        }

        return $content;
    }

    private function handleImages(array $files): array
    {
        $images = [];
        foreach ($files as $file) {
            $path = $this->uploadPictureServiceImpl->uploadPicture($file);
            $images[] = 'img:' . $path;
        }

        return $images;
    }
}