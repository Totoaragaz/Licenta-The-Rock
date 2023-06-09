<?php

namespace App\Service\Implementations;

use App\Entity\ThreadConversation;
use App\Entity\ThreadMessage;
use App\Repository\MessageRepository;
use App\Repository\ThreadMessageRepository;
use App\Transformer\ThreadMessageTransformer;

class ThreadMessageService
{
    public function __construct(
        private MessageRepository        $messageRepository,
        private ThreadMessageRepository  $threadMessageRepository,
        private ThreadMessageTransformer $transformer
    )
    {
    }

    public function addThreadMessagesToThreadConversation(ThreadConversation &$conversation, array $messageIds, string $requester): void
    {
        $messages = $this->messageRepository->getMessagesWithIds($messageIds);

        foreach ($messages as $message) {
            $threadMessage = $this->convertMessageToThreadMessage($conversation, $message, $requester);
            $conversation->addMessage($threadMessage);
        }
    }

    private function convertMessageToThreadMessage(ThreadConversation $conversation, array $message, string $requester): ThreadMessage
    {
        $threadMessage = new ThreadMessage();
        $threadMessage
            ->setConversation($conversation)
            ->setContent($message['content'])
            ->setAuthorMe($message['username'] == $requester);
        $this->threadMessageRepository->save($threadMessage);

        return $threadMessage;
    }

    public function convertConversationIntoMessageDtos(ThreadConversation $conversation): array
    {
        $messages = $conversation->getMessages();
        $messageDTOs = [];
        foreach ($messages as $message) {
            $messageDTOs[] = $this->transformer->transformThreadMessageIntoDto($message);
        }

        return $messageDTOs;
    }
}