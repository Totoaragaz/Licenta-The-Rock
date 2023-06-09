<?php

declare(strict_types=1);

namespace App\Manager;

use App\Dto\ViewThreadDto;
use App\Entity\Thread;
use App\Service\Implementations\ThreadService;

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
        $words = explode(' ', strtolower($query));
        return $this->threadService->getSearchedThreads($username, strtolower($query), $words, $page);
    }

    public function createThread(Thread $thread): bool
    {
        return $this->threadService->createThread($thread);
    }

    public function setThreadContent(Thread &$thread, array $text, array $images): void
    {
        $this->threadService->setThreadContent($thread, $text, $images);
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

    public function getChatThreads(string $username): array
    {
        return $this->threadService->getChatThreads($username);
    }

    public function editThreadContent(Thread &$thread, array $newContent, array $images): void
    {
        $this->threadService->editThreadContent($thread, $newContent, $images);
    }
}
