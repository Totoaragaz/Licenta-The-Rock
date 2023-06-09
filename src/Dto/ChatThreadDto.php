<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;

class ChatThreadDto
{
    private ?int $id;
    private ?string $title;
    private ?array $tags;
    private ?string $uploadDate;

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
        $this->uploadDate = $uploadDate->format('d/m/Y');

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
}
