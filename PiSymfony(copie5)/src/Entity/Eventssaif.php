<?php

namespace App\Entity;

use App\Repository\EventssaifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventssaifRepository::class)]
class Eventssaif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $destinationsaif = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $titlesaif = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $descriptionsaif = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $durationsaif = null;

    #[ORM\OneToMany(mappedBy: 'Eventssaif', targetEntity: Comments::class)]
    private Collection $es;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $imagesaif = null;

    #[ORM\Column(nullable: true)]
    private ?int $prixsaif = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $typesaif = null;

    #[ORM\Column(nullable: true)]
    private ?float $rating = null;

    #[ORM\OneToMany(mappedBy: 'eventR', targetEntity: Ratingsaif::class)]
    private Collection $valueR;

    public function __construct()
    {
        $this->es = new ArrayCollection();
        $this->valueR = new ArrayCollection();
    }

    public function getEntityCountOfRaiting(): int
    {
        return $this->valueR->count();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDestinationsaif(): ?string
    {
        return $this->destinationsaif;
    }

    public function setDestinationsaif(?string $destinationsaif): static
    {
        $this->destinationsaif = $destinationsaif;

        return $this;
    }

    public function getTitlesaif(): ?string
    {
        return $this->titlesaif;
    }

    public function setTitlesaif(?string $titlesaif): static
    {
        $this->titlesaif = $titlesaif;

        return $this;
    }

    public function getDescriptionsaif(): ?string
    {
        return $this->descriptionsaif;
    }

    public function setDescriptionsaif(?string $descriptionsaif): static
    {
        $this->descriptionsaif = $descriptionsaif;

        return $this;
    }

    public function getDurationsaif(): ?string
    {
        return $this->durationsaif;
    }

    public function setDurationsaif(?string $durationsaif): static
    {
        $this->durationsaif = $durationsaif;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getEs(): Collection
    {
        return $this->es;
    }

    public function addE(Comments $e): static
    {
        if (!$this->es->contains($e)) {
            $this->es->add($e);
            $e->setEventssaif($this);
        }

        return $this;
    }

    public function removeE(Comments $e): static
    {
        if ($this->es->removeElement($e)) {
            // set the owning side to null (unless already changed)
            if ($e->getEventssaif() === $this) {
                $e->setEventssaif(null);
            }
        }

        return $this;
    }

    public function getImagesaif(): ?string
    {
        return $this->imagesaif;
    }

    public function setImagesaif(?string $imagesaif): static
    {
        $this->imagesaif = $imagesaif;

        return $this;
    }

    public function getPrixsaif(): ?int
    {
        return $this->prixsaif;
    }

    public function setPrixsaif(?int $prixsaif): static
    {
        $this->prixsaif = $prixsaif;

        return $this;
    }

    public function getTypesaif(): ?string
    {
        return $this->typesaif;
    }

    public function setTypesaif(?string $typesaif): static
    {
        $this->typesaif = $typesaif;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Collection<int, Ratingsaif>
     */
    public function getValueR(): Collection
    {
        return $this->valueR;
    }

    public function addValueR(Ratingsaif $valueR): static
    {
        if (!$this->valueR->contains($valueR)) {
            $this->valueR->add($valueR);
            $valueR->setEventR($this);
        }

        return $this;
    }

    public function removeValueR(Ratingsaif $valueR): static
    {
        if ($this->valueR->removeElement($valueR)) {
            // set the owning side to null (unless already changed)
            if ($valueR->getEventR() === $this) {
                $valueR->setEventR(null);
            }
        }

        return $this;
    }
}
