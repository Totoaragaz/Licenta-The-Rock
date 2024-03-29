<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
/**
 * @ORM\Table(indexes={@Index(name="created_at_index", columns={"created_at"})})
 */
#[ORM\HasLifecycleCallbacks]
class Message
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conversation $conversation = null;

    #[ORM\Column(type: 'text')]
    private ?string $content = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    private bool $mine;

    public function isMine(): bool
    {
        return $this->mine;
    }

    public function setMine(bool $mine): Message
    {
        $this->mine = $mine;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
