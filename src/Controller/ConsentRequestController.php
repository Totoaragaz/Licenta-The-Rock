<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Manager\ConsentRequestManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/consentRequests', name: 'consentRequests.')]
class ConsentRequestController extends AbstractController
{
    public function __construct(
        private ConsentRequestManager $consentRequestManager,
    )
    {
    }

    #[Route('/create', name: 'create')]
    public function createConsentRequest(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $messages = json_decode($request->get('messages'));
        $recipient = $request->get('recipient');
        $threadId = $request->get('threadId');

        $this->consentRequestManager->createConsentRequest($threadId, $messages, $user->getUsername(), $recipient);

        return $this->json(Response::HTTP_CREATED);
    }

    #[Route('/accept', name: 'accept')]
    public function acceptConsentRequest(Request $request): Response
    {
        $requestId = intval($request->get('id'));
        $anonymous = $request->get('anonymous');

        $this->consentRequestManager->acceptConsentRequest($requestId, $anonymous);

        return $this->json(Response::HTTP_OK);
    }

    #[Route('/decline', name: 'decline')]
    public function declineConsentRequest(Request $request): Response
    {
        $requestId = (int)$request->get('id');

        $this->consentRequestManager->declineConsentRequest($requestId);

        return $this->json(Response::HTTP_OK);
    }

    #[Route('/{requestId}', name: 'getRequest', methods: 'GET')]
    public function getConsentRequest(int $requestId): Response
    {
        $consentRequest = $this->consentRequestManager->getConsentRequestPreview($requestId);

        return $this->json($consentRequest, Response::HTTP_CREATED);
    }
}
