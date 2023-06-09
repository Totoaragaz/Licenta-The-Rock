<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ThreadRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
#[ORM\Table(name: '`thread`')]
class Thread
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $uploadDate = null;

    #[ORM\Column]
    private ?bool $closed = false;

    #[ORM\ManyToOne(inversedBy: 'threads')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'threads')]
    private Collection $tags;

    #[ORM\OneToMany(mappedBy: 'thread', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'thread', targetEntity: ContentBit::class, orphanRemoval: true)]
    private Collection $content;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->content = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getUploadDate(): ?DateTimeInterface
    {
        return $this->uploadDate;
    }

    public function setUploadDate(DateTimeInterface $uploadDate): self
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }

    public function isClosed(): ?bool
    {
        return $this->closed;
    }

    public function setClosed(bool $closed): self
    {
        $this->closed = $closed;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addThread($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeThread($this);
        }

        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): Thread
    {
        $this->tags = $tags;
        return $this;
    }

    public function getTagNames(): array
    {
        $tagNames = [];
        foreach ($this->tags as $tag) {
            $tagNames[] = $tag->getName();
        }

        return $tagNames;

    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setThread($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getThread() === $this) {
                $comment->setThread(null);
            }
        }

        return $this;
    }

    public function getContent(): Collection
    {
        return $this->content;
    }

    public function addContent(ContentBit $content): self
    {
        if (!$this->content->contains($content)) {
            $this->content->add($content);
            $content->setThread($this);
        }

        return $this;
    }

    public function removeTextAndImages(): void
    {
        foreach ($this->content as $contentBit) {
            if ($contentBit->getType() != 'conversation') {
                $this->content->removeElement($contentBit);
            }
        }
    }

    public function removeContent(ContentBit $content): self
    {
        if ($this->content->removeElement($content)) {
            // set the owning side to null (unless already changed)
            if ($content->getThread() === $this) {
                $content->setThread(null);
            }
        }

        return $this;
    }

    public function hasConversations(): bool
    {
        foreach ($this->content as $contentBit) {
            if ($contentBit->getType() == 'conversation') {

                return true;
            }
        }

        return false;
    }
}
