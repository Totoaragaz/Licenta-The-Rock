<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;
use Doctrine\Common\Collections\Collection;

class ViewThreadDto
{
    private ?int $id;
    private ?string $title;
    private ?string $author;
    private ?array $content;
    private ?array $tags;
    private ?string $uploadDate;
    private ?bool $closed;
    private ?array $comments;

    public function getClosed(): ?bool
    {
        return $this->closed;
    }

    public function setClosed(?bool $closed): self
    {
        $this->closed = $closed;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getUploadDate(): ?string
    {
        return $this->uploadDate;
    }

    public function setUploadDate(?DateTime $uploadDate): self
    {
        $this->uploadDate = $uploadDate->format('d/m/Y h:i');

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?Collection $content): self
    {
        $this->content = [];
        foreach ($content as $contentBit) {
            switch ($contentBit->getType()) {

                case 'image':
                    $this->content[] = 'img:' . $contentBit->getContent();
                    break;

                case 'text':
                    $this->content[] = 'txt:' . $contentBit->getContent();
                    break;

                case 'conversation':
                    $conversation = [];
                    $conversation['helper'] = $contentBit->getConversation()->getHelper();
                    $conversation['messages'] = [];
                    $messages = $contentBit->getConversation()->getMessages();

                    foreach ($messages as $message) {
                        if ($message->isAuthorMe()) {
                            $conversation['messages'][] = 'me:' . $message->getContent();
                        } else {
                            $conversation['messages'][] = 'ot:' . $message->getContent();
                        }
                    }

                    $this->content[] = $conversation;
                    break;
            }
        }

        return $this;
    }

    public function getComments(): ?array
    {
        return $this->comments;
    }

    public function setComments(?array $comments): self
    {
        $this->comments = $comments;

        return $this;
    }
}
