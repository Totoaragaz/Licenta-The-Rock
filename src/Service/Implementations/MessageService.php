<?php

declare(strict_types=1);

namespace App\Service\Implementations;

use App\Entity\Message;
use App\Repository\MessageRepository;

class MessageService
{
    public function __construct(
        private MessageRepository $messageRepository
    )
    {
    }

    public function getMessages(int $conversationId): array
    {
        return $this->messageRepository->getMessages($conversationId);
    }

    public function createNewMessage(Message $message): void
    {
        $this->messageRepository->save($message, true);
    }
}