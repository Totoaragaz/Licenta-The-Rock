<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\ConversationManager;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

#[Route('/conversations', name: 'conversations.')]
class ConversationController extends AbstractController
{
    public function __construct(
        private UserManager $userManager,
        private ConversationManager $conversationManager,
    )
    {
    }

    #[Route('/', name: 'newConversations', methods: 'POST')]
    public function newConversation(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $otherUserId = $request->get('otherUserId', 0);

        $otherUser = $this->userManager->getUserObjectById($otherUserId);

        if (is_null($otherUser)) {
            throw new NotFoundHttpException('The user was not found');
        }

        $conversation = $this->conversationManager->findConversationByParticipants($otherUserId, $user->getId());

        if (count($conversation)) {
            throw new \Exception('Conversation exists.');
        }

        $conversation = $this->conversationManager->createNewConversation($user, $otherUser);

        return $this->json([
            'id' => $conversation->getId()
        ], Response::HTTP_CREATED, [], []);
    }

    #[Route('/', name: 'getConversations', methods: 'GET')]
    public function getConversations(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $conversations = $this->conversationManager->getConversations($user->getId());

        $hubUrl = $this->getParameter('mercure.default_hub');

        //$this->addLink($request, new Link('mercure', $hubUrl));

        return $this->json([$conversations ,$hubUrl]);
    }
}
