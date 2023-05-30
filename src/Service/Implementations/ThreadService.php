<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Dto\ViewThreadDto;
use App\Entity\Thread;
use App\Repository\ThreadRepository;
use App\Transformer\CommentTransformer;
use App\Transformer\ThreadTransformer;

class ThreadService
{
    public function __construct(
        private ThreadRepository $threadRepository,
        private ThreadTransformer $transformer,
        private CommentTransformer $commentTransformer,
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
                $threadDTOs[] = $this->transformer->transformThreadIntoSearchDto($thread);
            }

            return $threadDTOs;
        }

        return [];
    }

    public function createThread(Thread $thread): bool
    {
        $thread->setUploadDate(date_create());
        return $this->threadRepository->createThread($thread);
    }

    public function setThreadContent(array $text, array $images): array
    {
        $content = [];
        $imageNumber = 0;
        for ($i = 0; $i < sizeof($text); $i++) {
            if ($text[$i] != null) {
                $content[] = $text[$i];
            }
            if ($imageNumber < sizeof($images)) {
                $content[] = $images[$imageNumber];
                $imageNumber++;
            }
        }

        return $content;
    }

    public function getSearchedThreads(string $username, string $searchQuery, array $words, int $page): array
    {
        $threads = $this->threadRepository->getSearchedThreadsWithPage($username, $searchQuery, $words, $page);

        if ($threads) {
            $threadDTOs = [];
            foreach ($threads as $thread) {
                $threadDTOs[] = $this->transformer->transformThreadIntoSearchDto($thread);
            }

            return $threadDTOs;
        }

        return [];
    }

    public function getThreadDtoById(string $threadId): ViewThreadDto
    {
        $thread = $this->threadRepository->getThreadById($threadId);
        $comments = $thread->getComments();
        $commentDTOs = [];
        foreach ($comments as $comment) {
            $commentDTOs[] = $this->commentTransformer->transformCommentIntoDto($comment);
        }

        return $this->transformer->transformThreadIntoViewDto($thread, $commentDTOs);
    }

    public function getThreadObjectById(string $threadId): Thread
    {
        return $this->threadRepository->getThreadById($threadId);
    }

    public function deleteThread(string $threadId): void
    {
        $thread = $this->threadRepository->getThreadById($threadId);
        foreach ($thread->getContent() as $contentBit) {
            if (file_exists('img/' . $contentBit)) {
                $this->uploadPictureServiceImpl->deletePicture($contentBit);
            }
        }
        $this->threadRepository->deleteThread($threadId);
    }
}