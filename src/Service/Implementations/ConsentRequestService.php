<?php

namespace App\Service\Implementations;

use App\Entity\ConsentRequest;
use App\Entity\Thread;
use App\Entity\ThreadConversation;
use App\Repository\ConsentRequestRepository;
use App\Repository\UserRepository;

class ConsentRequestService
{
    public function __construct(
        private ConsentRequestRepository $consentRequestRepository,
        private UserRepository           $userRepository,
    )
    {
    }

    public function createConsentRequest(Thread $thread, ThreadConversation $conversation, string $requesterUsername, string $recipientUsername): void
    {
        $requester = $this->userRepository->getUserByUsername($requesterUsername);
        $recipient = $this->userRepository->getUserByUsername($recipientUsername);

        $consentRequest = new ConsentRequest();
        $consentRequest
            ->setConversation($conversation)
            ->setThread($thread)
            ->setRequester($requester)
            ->setRecipient($recipient);

        $requester->addOutgoingConsentRequest($consentRequest);
        $recipient->addIncomingConsentRequest($consentRequest);

        $this->consentRequestRepository->save($consentRequest, 1);
        $this->userRepository->updateUserRepo($requester);
        $this->userRepository->updateUserRepo($recipient);
    }

    public function getConsentRequestPreview(int $requestId): array
    {
        return $this->consentRequestRepository->getConsentRequestPreview($requestId);
    }

    public function deleteConsentRequestById(int $requestId): void
    {
        $this->consentRequestRepository->remove($this->consentRequestRepository->findOneBy(['id' => $requestId]), 1);
    }

    public function deleteConsentRequestByObject(ConsentRequest $consentRequest): void
    {
        $this->consentRequestRepository->remove($consentRequest, 1);
    }

    public function getConsentRequestObjectById(int $requestId): ?ConsentRequest
    {
        return $this->consentRequestRepository->findOneBy(['id' => $requestId]);
    }
}