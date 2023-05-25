<?php

namespace App\Dto;

use DateTime;

class CommentDto
{
    private ?string $id;
    private ?string $content;
    private ?string $author;
    private ?string $uploadDate;
    private ?string $authorImage;

    public function __construct()
    {
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): CommentDto
    {
        $this->content = $content;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): CommentDto
    {
        $this->author = $author;
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

    public function getAuthorImage(): ?string
    {
        return $this->authorImage;
    }

    public function setAuthorImage(?string $authorImage): CommentDto
    {
        $this->authorImage = $authorImage;
        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): CommentDto
    {
        $this->id = $id;
        return $this;
    }
}