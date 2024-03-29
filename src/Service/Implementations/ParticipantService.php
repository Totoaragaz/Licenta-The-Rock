<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;

class ParticipantService
{
    public function __construct(
        private ParticipantRepository $participantRepository
    )
    {
    }

    public function getOtherParticipant(int $conversationId, int $userId): ?Participant
    {
        return $this->participantRepository->getOtherParticipant($conversationId, $userId);
    }
}