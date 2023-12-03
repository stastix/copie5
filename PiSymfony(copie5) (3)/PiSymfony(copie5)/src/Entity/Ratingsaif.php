<?php

namespace App\Entity;

use App\Repository\RatingsaifRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingsaifRepository::class)]
class Ratingsaif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $idUser = null;

    #[ORM\Column(nullable: true)]
    private ?int $valueRaiting = null;

    #[ORM\ManyToOne(inversedBy: 'valueR')]
    private ?Eventssaif $eventR = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getValueRaiting(): ?int
    {
        return $this->valueRaiting;
    }

    public function setValueRaiting(?int $valueRaiting): static
    {
        $this->valueRaiting = $valueRaiting;

        return $this;
    }

    public function getEventR(): ?Eventssaif
    {
        return $this->eventR;
    }

    public function setEventR(?Eventssaif $eventR): static
    {
        $this->eventR = $eventR;

        return $this;
    }
}
