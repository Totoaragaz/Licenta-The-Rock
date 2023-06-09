<?php

namespace App\Security\Voter;

use App\Entity\Conversation;
use App\Entity\User;
use App\Manager\ConversationManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConversationVoter extends Voter
{
    const VIEW = 'view';

    public function __construct(
        private ConversationManager $conversationManager,
    )
    {
    }


    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute == self::VIEW && $subject instanceof Conversation;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        return $this->conversationManager->checkIfUserIsParticipant($subject->getId(), $user->getId());
    }
}