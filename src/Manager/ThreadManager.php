<?php

namespace App\Manager;

use App\Service\Implementations\ThreadService;

class ThreadManager
{
    public function __construct(
        private ThreadService $threadService
    )
    {
    }

    public function getAllThreads(string $username, int $page): array
    {
        return $this->threadService->getAllThreads($username, $page);
    }

    public function getAllThreadsNumberOfPages(string $username): int
    {
        return $this->threadService->getAllThreadsNumberOfPages($username);
    }

}