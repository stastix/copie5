<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SponsorRepository;
use App\Entity\Evenements;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: SponsorRepository::class)]

class Sponsor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $idsponsor;
    
    #[ORM\Column(name: "NomSponsor", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le nom du sponsor est obligatoire")]
    private $nomsponsor;

    #[ORM\Column(name: "Secteurdactivite", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Le secteur d'activité est obligatoire")]

    private $secteurdactivite;

    #[ORM\Column(name: "adresseSponsor", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "L'adresse du sponsor est obligatoire")]

    private $adressesponsor;

    #[ORM\Column(name: "numtelSponsor", type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire")]
    #[Assert\Type(type: "integer", message: "Le numéro de téléphone doit être un nombre")]
    private $numtelsponsor;

    #[ORM\Column(name: "emailSponsor", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "L'email du sponsor est obligatoire")]
    #[Assert\Email(message: "L'email n'est pas valide")]
    private $emailsponsor;

    #[ORM\Column(name: "duree", type: "string", length: 255, nullable: true)]
    #[Assert\NotBlank (message:"Quelle est la durée du sponsoring?")]
    private $duree;




    

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }


    public function getIdsponsor(): ?int
    {
        return $this->idsponsor;
    }

    public function getNomsponsor(): ?string
    {
        return $this->nomsponsor;
    }

    public function setNomsponsor(string $nomsponsor): static
    {
        $this->nomsponsor = $nomsponsor;

        return $this;
    }

    public function getSecteurdactivite(): ?string
    {
        return $this->secteurdactivite;
    }

    public function setSecteurdactivite(string $secteurdactivite): static
    {
        $this->secteurdactivite = $secteurdactivite;

        return $this;
    }

    public function getAdressesponsor(): ?string
    {
        return $this->adressesponsor;
    }

    public function setAdressesponsor(string $adressesponsor): static
    {
        $this->adressesponsor = $adressesponsor;

        return $this;
    }

    public function getNumtelsponsor(): ?int
    {
        return $this->numtelsponsor;
    }

    public function setNumtelsponsor(int $numtelsponsor): static
    {
        $this->numtelsponsor = $numtelsponsor;

        return $this;
    }

    public function getEmailsponsor(): ?string
    {
        return $this->emailsponsor;
    }

    public function setEmailsponsor(string $emailsponsor): static
    {
        $this->emailsponsor = $emailsponsor;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): static
    {
        $this->duree = $duree;

        return $this;
    }


}
