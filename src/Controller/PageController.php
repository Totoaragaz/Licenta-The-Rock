<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Manager\ThreadManager;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    public function __construct(
        private ThreadManager $threadManager
    )
    {
    }

    #[Route(path: '/setSession', name: 'setSession')]
    public function setSession(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $session = $request->getSession();
        $session->set('darkMode', $user->getDarkMode());
        $session->set('mainColumn', $user->getMainColumn());
        $session->set('chatColumn', $user->getChatColumn());
        $session->set('friendColumn', $user->getFriendColumn());
        $session->set('incomingRequests', true);
        $session->set('outgoingRequests', true);
        $session->set('friends', true);
        $session->set('recents', true);
        $session->set('activeConversation', 0);

        $token = (new Builder(new JoseEncoder(), ChainedFormatter::default()))
            ->withClaim('mercure', ['subscribe' => [sprintf("/%s", $user->getUsername())]])
            ->getToken(
                new Sha256(),
                InMemory::plainText($this->getParameter('mercure_secret_key'))
            );

        $response = $this->redirectToRoute('forum');

        $response->headers->setCookie(
            new Cookie(
                name: 'mercureAuthorization',
                value: $token->toString(),
                expire: time() + (10 * 365 * 24 * 60 * 60),
                path: '/',
                secure: false,
                httpOnly: true,
                raw: false,
                sameSite: 'strict'
            )
        );

        return $response;
    }

    #[Route(path: '/setMainColumn', name: 'setMainColumn')]
    public function setMainColumn(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('mainColumn', !$session->get('mainColumn'));

        return $this->json(Response::HTTP_OK);
    }

    #[Route(path: '/setFriendColumn', name: 'setFriendColumn')]
    public function setFriendColumn(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('friendColumn', !$session->get('friendColumn'));

        return $this->json(Response::HTTP_OK);
    }

    #[Route(path: '/setChatColumn', name: 'setChatColumn')]
    public function setChatColumn(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('chatColumn', !$session->get('chatColumn'));

        return $this->json(Response::HTTP_OK);
    }

    #[Route(path: '/setIncomingRequests', name: 'setIncomingRequests')]
    public function setIncomingRequests(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('incomingRequests', !$session->get('incomingRequests'));

        return $this->json(Response::HTTP_OK);
    }

    #[Route(path: '/setOutgoingRequests', name: 'setOutgoingRequests')]
    public function setOutgoingRequests(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('outgoingRequests', !$session->get('outgoingRequests'));

        return $this->json(Response::HTTP_OK);
    }

    #[Route(path: '/setFriends', name: 'setFriends')]
    public function setFriends(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('friends', !$session->get('friends'));

        return $this->json(Response::HTTP_OK);
    }

    #[Route(path: '/setRecents', name: 'setRecents')]
    public function setRecents(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('recents', !$session->get('recents'));

        return $this->json(Response::HTTP_OK);
    }

    #[Route(path: '/getChatThreads', name: 'getChatThreads')]
    public function getChatThreads(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $threads = $this->threadManager->getChatThreads($user->getUsername());

        return $this->json($threads, Response::HTTP_OK, [], [
            'attributes' => ['id', 'title', 'tags', 'uploadDate']
        ]);
    }

    #[Route(path: '/getThreadPreview', name: 'getThreadPreview')]
    public function getThreadPreview(Request $request): Response
    {
        $threadId = $request->get('threadId');
        $thread = $this->threadManager->getThreadDtoById($threadId);

        return $this->json($thread, Response::HTTP_OK, [], []);
    }

    #[Route('/getActiveConversation', name: 'getActiveConversation', methods: ['GET'])]
    public function getActiveConversation(Request $request): Response
    {
        $session = $request->getSession();
        if ($session->get('activeConversation') != 0) {
            $conversation = explode(',', $session->get('activeConversation'));

            return $this->json(['conversation' => $conversation[0], 'recipient' => $conversation[1]], Response::HTTP_OK);
        } else {

            return $this->json(null, Response::HTTP_NO_CONTENT);
        }
    }

    #[Route('/setActiveConversation', name: 'setActiveConversation', methods: ['POST'])]
    public function setActiveConversation(Request $request): Response
    {
        $session = $request->getSession();
        $conversationId = $request->get('conversationId');
        $recipient = $request->get('recipient');
        $session->set('activeConversation', $conversationId . ',' . $recipient);

        return $this->json(Response::HTTP_OK);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout(): void
    {
        session_destroy();
    }
}
