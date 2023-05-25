<?php

declare(strict_types=1);

namespace App\Manager;

use App\Dto\ViewThreadDto;
use App\Entity\Thread;
use App\Service\Implementations\ThreadService;
use App\Transformer\ThreadTransformer;
use Doctrine\Common\Collections\Collection;

class ThreadManager
{
    public function __construct(
        private ThreadService $threadService,
    )
    {
    }

    public function getAllThreads(string $username, int $page): array
    {
        return $this->threadService->getAllThreads($username, $page);
    }

    public function getSearchedThreads(string $username, string $query, int $page): array
    {
        $words = explode(' ',strtolower($query));
        return $this->threadService->getSearchedThreads($username, strtolower($query), $words, $page);
    }

    public function createThread(Thread $thread): bool
    {
        return $this->threadService->createThread($thread);
    }

    public function setThreadContent(array $text, array $images): array
    {
        return $this->threadService->setThreadContent($text,$images);
    }

    public function getThreadDtoById(string $threadId): ViewThreadDto
    {
        return $this->threadService->getThreadDtoById($threadId);
    }

    public function deleteThread(string $threadId): void
    {
        $this->threadService->deleteThread($threadId);
    }

    public function getThreadObjectById(string $threadId): Thread
    {
        return $this->threadService->getThreadObjectById($threadId);
    }
}