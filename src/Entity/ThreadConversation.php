<?php

namespace App\Entity;

use App\Repository\ThreadConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThreadConversationRepository::class)]
class ThreadConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'conversation', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContentBit $contentBit = null;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: ThreadMessage::class, orphanRemoval: true)]
    private Collection $messages;

    #[ORM\Column(length: 255)]
    private ?string $helper = null;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentBit(): ?ContentBit
    {
        return $this->contentBit;
    }

    public function setContentBit(ContentBit $contentBit): self
    {
        $this->contentBit = $contentBit;

        return $this;
    }

    /**
     * @return Collection<int, ThreadMessage>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(ThreadMessage $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(ThreadMessage $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

        return $this;
    }

    public function getHelper(): ?string
    {
        return $this->helper;
    }

    public function setHelper(string $helper): self
    {
        $this->helper = $helper;

        return $this;
    }
}
