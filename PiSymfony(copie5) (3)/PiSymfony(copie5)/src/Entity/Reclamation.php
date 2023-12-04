<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $TextReclamation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $CibleReclamation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateReclamation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $EtatReclamation = "on hold";



    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?User $UseName = null;

  

    public function __construct()
    {
        // Initialize EtatReclamation to "on hold" when the object is created
        $this->EtatReclamation = 'on hold';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTextReclamation(): ?string
    {
        return $this->TextReclamation;
    }

    public function setTextReclamation(string $TextReclamation): static
    {
        $this->TextReclamation = $TextReclamation;

        return $this;
    }

    public function getCibleReclamation(): ?string
    {
        return $this->CibleReclamation;
    }

    public function setCibleReclamation(string $CibleReclamation): static
    {
        $this->CibleReclamation = $CibleReclamation;

        return $this;
    }

    public function getDateReclamation(): ?\DateTimeInterface
    {
        return $this->dateReclamation;
    }

    public function setDateReclamation(\DateTimeInterface $dateReclamation): static
    {
        $this->dateReclamation = $dateReclamation;

        return $this;
    }

    public function getEtatReclamation(): ?string
    {
        return $this->EtatReclamation;
    }

    public function setEtatReclamation(?string $EtatReclamation): static
    {
        $this->EtatReclamation = $EtatReclamation;

        return $this;
    }

  

    public function getUseName(): ?User
    {
        return $this->UseName;
    }

    public function setUseName(?User $UseName): static
    {
        $this->UseName = $UseName;

        return $this;
    }

  
}
