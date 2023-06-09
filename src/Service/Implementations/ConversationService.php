<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Repository\ParticipantRepository;

class ConversationService
{
    public function __construct(
        private ConversationRepository $conversationRepository,
        private ParticipantRepository  $participantRepository
    )
    {
    }

    public function findConversationByParticipants(int $otherUserId, int $myId): array
    {
        return $this->conversationRepository->findConversationByParticipants($otherUserId, $myId);
    }

    public function createNewConversation(User $user, User $otherUser): Conversation
    {
        $conversation = new Conversation();
        $participant1 = new Participant();
        $participant1->setUser($user);
        $participant1->setConversation($conversation);

        $participant2 = new Participant();
        $participant2->setUser($otherUser);
        $participant2->setConversation($conversation);

        $this->conversationRepository->createNewConversation($conversation);
        $this->participantRepository->createParticipants($participant1, $participant2);

        return $conversation;
    }

    public function getConversations(int $userId): array
    {
        $conversations = $this->conversationRepository->getConversations($userId);

        foreach ($conversations as $key => $conversation) {
            if (!is_null($conversation['createdAt'])) {
                $conversations[$key]['createdAt'] = date_format($conversation['createdAt'], "H:i j/n/y");
            }
        }

        return $conversations;
    }

    public function checkIfUserIsParticipant(int $conversationId, int $userId): bool
    {
        return !is_null($this->conversationRepository->checkIfUserIsParticipant($conversationId, $userId));
    }

    public function getConversationById(int $id): ?Conversation
    {
        return $this->conversationRepository->findOneBy(['id' => $id]);
    }

    public function updateConversation(Conversation $conversation): void
    {
        $this->conversationRepository->save($conversation, true);
    }
}