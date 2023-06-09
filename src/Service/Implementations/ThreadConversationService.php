<?php

namespace App\Service\Implementations;

use App\Entity\ThreadConversation;
use App\Repository\ThreadConversationRepository;

class ThreadConversationService
{
    public function __construct(
        private ThreadConversationRepository $threadConversationRepository,
    )
    {
    }

    public function persistThreadConversation(ThreadConversation $conversation): void
    {
        $this->threadConversationRepository->save($conversation);
    }

    public function getConversationById(int $conversationId): ?ThreadConversation
    {
        return $this->threadConversationRepository->findOneBy(['id' => $conversationId]);
    }

    public function deleteThreadConversation(?ThreadConversation $conversation): void
    {
        $this->threadConversationRepository->remove($conversation);
    }
}