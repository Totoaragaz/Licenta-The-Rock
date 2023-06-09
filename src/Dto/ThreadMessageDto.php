<?php

declare(strict_types=1);

namespace App\Dto;

class ThreadMessageDto
{
    private string $content;
    private bool $mine;

    public function __construct()
    {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): ThreadMessageDto
    {
        $this->content = $content;
        return $this;
    }

    public function isMine(): bool
    {
        return $this->mine;
    }

    public function setMine(bool $mine): ThreadMessageDto
    {
        $this->mine = $mine;
        return $this;
    }
}
