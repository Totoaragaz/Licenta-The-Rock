<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;

class ViewThreadDto
{
    private ?int $id;
    private ?string $title;
    private ?string $author;
    private ?array $content;
    private ?array $tags;
    private ?string $uploadDate;
    private ?bool $closed;

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
        $this->uploadDate = $uploadDate->format('d/m/Y h:i:s');
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

    public function setContent(?array $content): self
    {
        $this->content = $content;
        return $this;
    }
}