<?php

namespace App\Manager;

use App\Entity\ThreadConversation;
use App\Service\Implementations\ConsentRequestService;
use App\Service\Implementations\ThreadConversationService;
use App\Service\Implementations\ThreadMessageService;
use App\Service\Implementations\ThreadService;

class ConsentRequestManager
{
    public function __construct(
        private ConsentRequestService     $consentRequestService,
        private ThreadConversationService $threadConversationService,
        private ThreadMessageService      $threadMessageService,
        private ThreadService             $threadService,
    )
    {
    }

    public function createConsentRequest(int $threadId, array $messages, string $requester, string $recipient): void
    {
        $conversation = new ThreadConversation();
        $conversation->setHelper($recipient);
        $this->threadMessageService->addThreadMessagesToThreadConversation($conversation, $messages, $requester);

        $this->threadConversationService->persistThreadConversation($conversation);

        $thread = $this->threadService->getThreadObjectById($threadId);

        $this->consentRequestService->createConsentRequest($thread, $conversation, $requester, $recipient);
    }

    public function getConsentRequestPreview(int $requestId): array
    {
        $request = $this->consentRequestService->getConsentRequestPreview($requestId);
        $consentRequest = [];

        $consentRequest['thread'] = $this->threadService->getThreadDtoById($request[0]['threadId']);
        $consentRequest['recipient'] = $request[0]['recipient'];
        $consentRequest['requester'] = $request[0]['requester'];
        $consentRequest['conversation'] = $this->threadMessageService->convertConversationIntoMessageDtos(
            $this->threadConversationService->getConversationById($request[0]['conversationId'])
        );

        return $consentRequest;
    }

    public function acceptConsentRequest(int $requestId, bool $anonymous): void
    {
        $consentRequest = $this->consentRequestService->getConsentRequestObjectById($requestId);

        $this->threadService->addThreadConversation($consentRequest->getThread(), $consentRequest->getConversation(), $anonymous);

        $this->consentRequestService->deleteConsentRequestByObject($consentRequest);
    }

    public function declineConsentRequest(int $requestId): void
    {
        $consentRequest = $this->consentRequestService->getConsentRequestObjectById($requestId);

        $this->threadConversationService->deleteThreadConversation($consentRequest->getConversation());

        $this->consentRequestService->deleteConsentRequestByObject($consentRequest);
    }
}