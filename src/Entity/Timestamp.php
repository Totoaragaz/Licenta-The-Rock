<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait Timestamp
{
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function getCreatedAt(): string
    {
        return $this->createdAt->format("H:i j/n/y");
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = date_create();
    }
}