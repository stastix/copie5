<?php

namespace App\Entity;

use App\Validator\Constraints as CustomAssert; // Add this line

use Doctrine\ORM\Mapping as ORM;


use App\Repository\CommentsRepository;


#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $userName = null;

    #[ORM\Column(length: 255)]

    private ?string $context = null;
 

    #[ORM\Column]
    private ?int $idEvent = null;

    #[ORM\ManyToOne(inversedBy: 'es')]
    private ?Eventssaif $Eventssaif = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): static
    {
        $this->userName = $userName;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(string $context): static
    {
        $this->context = $context;

        return $this;
    }

    public function getIdEvent(): ?int
    {
        return $this->idEvent;
    }

    public function setIdEvent(int $idEvent): static
    {
        $this->idEvent = $idEvent;

        return $this;
    }

    public function getEventssaif(): ?Eventssaif
    {
        return $this->Eventssaif;
    }

    public function setEventssaif(?Eventssaif $Eventssaif): static
    {
        $this->Eventssaif = $Eventssaif;

        return $this;
    }
}
