<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Repository\ThreadRepository;
use App\Transformer\ThreadTransformer;

class ThreadService
{
    public function __construct(
        private ThreadRepository $threadRepository,
        private ThreadTransformer $transformer
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
}