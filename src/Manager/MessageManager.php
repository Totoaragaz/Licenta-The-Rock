<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Message;
use App\Service\Implementations\MessageService;

class MessageManager
{
    public function __construct(
        private MessageService $messageService
    )
    {
    }

    public function getMessages(int $conversationId): array
    {
        return $this->messageService->getMessages($conversationId);
    }

    public function createNewMessage(Message $message): void
    {
        $this->messageService->createNewMessage($message);
    }
}
