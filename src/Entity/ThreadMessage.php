<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ThreadMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThreadMessageRepository::class)]
class ThreadMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private ?string $content = null;

    #[ORM\Column]
    private ?bool $authorMe = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'], inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ThreadConversation $conversation = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function isAuthorMe(): ?bool
    {
        return $this->authorMe;
    }

    public function setAuthorMe(bool $authorMe): self
    {
        $this->authorMe = $authorMe;

        return $this;
    }

    public function getConversation(): ?ThreadConversation
    {
        return $this->conversation;
    }

    public function setConversation(?ThreadConversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }
}
