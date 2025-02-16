<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_read = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isRead(): ?bool
    {
        return $this->is_read;
    }

    public function setRead(?bool $is_read): static
    {
        $this->is_read = $is_read;

        return $this;
    }
}
