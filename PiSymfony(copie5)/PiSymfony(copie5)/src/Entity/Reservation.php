<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $userId = null;

    #[ORM\Column]
    private ?int $eventId = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbAdults = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbKids = null;

    #[ORM\Column(nullable: true)]
    private ?int $prixR = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getEventId(): ?int
    {
        return $this->eventId;
    }

    public function setEventId(int $eventId): static
    {
        $this->eventId = $eventId;

        return $this;
    }

    public function getNbAdults(): ?int
    {
        return $this->nbAdults;
    }

    public function setNbAdults(?int $nbAdults): static
    {
        $this->nbAdults = $nbAdults;

        return $this;
    }

    public function getNbkids(): ?int
    {
        return $this->nbKids;
    }

    public function setNbkids(?int $nbkids): static
    {
        $this->nbKids = $nbkids;

        return $this;
    }

    public function getPrixR(): ?int
    {
        return $this->prixR;
    }

    public function setPrixR(?int $prixR): static
    {
        $this->prixR = $prixR;

        return $this;
    }
}
