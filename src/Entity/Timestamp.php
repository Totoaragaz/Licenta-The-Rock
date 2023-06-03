<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Faker\Core\DateTime;

trait Timestamp
{
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new DateTime();
    }
}