<?php

namespace App\Manager;

use App\Entity\Conversation;
use App\Entity\User;
use App\Service\Implementations\ConversationService;

class ConversationManager
{
    public function __construct(
        private ConversationService $conversationService
    )
    {
    }

    public function findConversationByParticipants(int $otherUserId, int $myId): array
    {
        return $this->conversationService->findConversationByParticipants($otherUserId, $myId);
    }

    public function createNewConversation(User $user, User $otherUser): Conversation
    {
        return $this->conversationService->createNewConversation($user, $otherUser);
    }

    public function getConversations(int $userId): array
    {
        return $this->conversationService->getConversations($userId);
    }

    public function checkIfUserIsParticipant(int $conversationId, int $userId): bool
    {
        return $this->conversationService->checkIfUserIsParticipant($conversationId, $userId);
    }

    public function getConversationById(int $id): ?Conversation
    {
        return $this->conversationService->getConversationById($id);
    }

    public function updateConversation(Conversation $conversation): void
    {
        $this->conversationService->updateConversation($conversation);
    }
}