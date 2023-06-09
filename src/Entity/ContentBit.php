<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContentBitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContentBitRepository::class)]
class ContentBit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'content')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Thread $thread = null;

    #[ORM\OneToOne(mappedBy: 'contentBit', cascade: ['persist', 'remove'])]
    private ?ThreadConversation $conversation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getThread(): ?Thread
    {
        return $this->thread;
    }

    public function setThread(?Thread $thread): self
    {
        $this->thread = $thread;

        return $this;
    }

    public function getConversation(): ?ThreadConversation
    {
        return $this->conversation;
    }

    public function setConversation(ThreadConversation $conversation): self
    {
        // set the owning side of the relation if necessary
        if ($conversation->getContentBit() !== $this) {
            $conversation->setContentBit($this);
        }

        $this->conversation = $conversation;

        return $this;
    }
}
