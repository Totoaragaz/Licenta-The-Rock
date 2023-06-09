<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Manager\ConversationManager;
use App\Manager\MessageManager;
use App\Manager\ParticipantManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/messages', name: 'messages.')]
class MessageController extends AbstractController
{
    const ATTRIBUTES_TO_SERIALIZE = ['id', 'content', 'createdAt', 'mine'];

    public function __construct(
        private MessageManager      $messageManager,
        private ConversationManager $conversationManager,
        private ParticipantManager  $participantManager,
    )
    {
    }

    #[Route('/{id}', name: 'getMessages', methods: 'GET')]
    public function getMessages(int $id): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $conversation = $this->conversationManager->getConversationById($id);
        $this->denyAccessUnlessGranted('view', $conversation);
        $messages = $this->messageManager->getMessages($conversation->getId());

        foreach ($messages as $message) {
            if ($message->getUser()->getId() == $user->getId()) {
                $message->setMine(true);
            } else {
                $message->setMine(false);
            }
        }

        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }

    #[Route('/{id}', name: 'createMessage', methods: 'POST')]
    public function createMessage(Request $request, int $id, SerializerInterface $serializer, HubInterface $hub): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $conversation = $this->conversationManager->getConversationById($id);

        $recipient = $this->participantManager->getOtherParticipant($conversation->getId(), $user->getId());

        $content = $request->get('content');

        if (is_null($content)) {
            throw new Exception('Message cannot be null');
        }

        $message = new Message();
        $message->setContent($content);
        $message->setUser($user);

        $conversation->addMessage($message);
        $conversation->setLastMessage($message);

        $this->messageManager->createNewMessage($message);
        $this->conversationManager->updateConversation($conversation);

        $message->setMine(false);
        $messageSerialized = $serializer->serialize($message, 'json', [
            'attributes' => ['id', 'content', 'createdAt', 'mine', 'conversation' => ['id']]
        ]);

        $update = new Update(
            sprintf('/conversations/%s', $recipient->getUser()->getUsername()),
            $messageSerialized
        );

        $hub->publish($update);

        $message->setMine(true);

        return $this->json($message, Response::HTTP_CREATED, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE
        ]);
    }
}
