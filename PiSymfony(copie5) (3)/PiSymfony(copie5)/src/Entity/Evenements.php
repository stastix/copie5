<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvenementsRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Sponsor;


#[ORM\Entity(repositoryClass: EvenementsRepository::class)]

class Evenements
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column]
private ?int $idevenement = null;


#[ORM\Column(length: 255)]
#[Assert\NotBlank (message:"Le titre est obligatoire")]
private ?string  $titre = null;


#[ORM\Column(length: 255)]
#[Assert\NotBlank (message:"La description est obligatoire")]

private ?string $description= null;

#[ORM\Column( type: 'date')]
#[Assert\NotBlank(message: "La date est obligatoire")]
#[Assert\Type(type: '\DateTimeInterface')]

private ?\DateTimeInterface $dateDepart = null;



#[ORM\Column]
#[Assert\Positive]
private ?float $prix = null;

#[ORM\Column(length: 255)]
#[Assert\NotBlank (message:"Le type d'évenement est obligatoire")]

private ?string  $typeevenement = null;


#[ORM\Column]
#[Assert\NotBlank (message:"Précisez le guide de votre évenement !")]

private ?int $guideId = null;

   

    
#[ORM\Column(length: 255)]
#[Assert\NotBlank (message:"La destination est obligatoire")]
private ?string $destination = null;


#[ORM\Column(length: 255)]
#[Assert\NotBlank (message:"Inserez une image")]
private ?string $image = null;

#[ORM\Column(length: 255)]
private ?string $sponsorevenement = null;




public function __construct()
{
    $this->sponsors = new ArrayCollection();
}

    public function getIdevenement(): ?int
    {
        return $this->idevenement;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): static
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getTypeevenement(): ?string
    {
        return $this->typeevenement;
    }

    public function setTypeevenement(string $typeevenement): static
    {
        $this->typeevenement = $typeevenement;

        return $this;
    }

    public function getGuideId(): ?int
    {
        return $this->guideId;
    }

    public function setGuideId(int $guideId): static
    {
        $this->guideId = $guideId;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getSponsorevenement(): ?string
    {
        return $this->sponsorevenement;
    }

    public function setSponsorevenement(string $sponsorevenement): static
    {
        $this->sponsorevenement = $sponsorevenement;

        return $this;
    }

}
