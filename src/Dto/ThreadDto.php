<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;

class ThreadDto
{
    private ?string $title;
    private ?string $author;
    private ?array $tags;
    private ?string $uploadDate;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): ThreadDto
    {
        $this->title = $title;
        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): ThreadDto
    {
        $this->tags = $tags;
        return $this;
    }

    public function getUploadDate(): ?string
    {
        return $this->uploadDate;
    }

    public function setUploadDate(?DateTime $uploadDate): ThreadDto
    {
        $this->uploadDate = $uploadDate->format('d/m/Y');
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): ThreadDto
    {
        $this->author = $author;
        return $this;
    }
}