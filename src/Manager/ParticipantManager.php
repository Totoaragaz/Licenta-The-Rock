<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Participant;
use App\Service\Implementations\ParticipantService;

class ParticipantManager
{
    public function __construct(
        private ParticipantService $participantService
    )
    {
    }

    public function getOtherParticipant(int $conversationId, int $userId): ?Participant
    {
        return $this->participantService->getOtherParticipant($conversationId, $userId);
    }
}
