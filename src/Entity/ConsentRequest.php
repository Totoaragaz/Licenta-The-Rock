<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ConsentRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsentRequestRepository::class)]
class ConsentRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ThreadConversation $conversation = null;

    #[ORM\ManyToOne(inversedBy: 'outgoingConsentRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $requester = null;

    #[ORM\ManyToOne(inversedBy: 'incomingConsentRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $recipient = null;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thread $thread = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConversation(): ?ThreadConversation
    {
        return $this->conversation;
    }

    public function setConversation(ThreadConversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getRequester(): ?User
    {
        return $this->requester;
    }

    public function setRequester(?User $requester): self
    {
        $this->requester = $requester;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    public function setThread(Thread $thread): static
    {
        $this->thread = $thread;

        return $this;
    }
}
