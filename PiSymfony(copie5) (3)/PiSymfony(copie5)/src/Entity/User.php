<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'veuillez remplir tous les champs obligatoires')]
    #[Assert\Email(message: 'L\'adresse email "{{ value }}" n\'est pas valide.')]
    private ?string $email = null;

    #[ORM\Column(name:"Role", type:"string", columnDefinition:"ENUM('ADMIN', 'GUIDE', 'CLIENT')")]
    #[Assert\NotBlank(message: 'veuillez remplir tous les champs obligatoires')]
    private ?string $role = null;
    #[ORM\Column(name: 'cartefidelite_id', type: 'integer', nullable: true)]
    private ?int $cartefideliteId;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Cartefidelite $cartefidelite = null; 


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'veuillez remplir tous les champs obligatoires')]
    #[Assert\Length(min: 4, minMessage: 'Le nom doit comporter au moins {{ limit }} caractères')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'veuillez remplir tous les champs obligatoires')]
    #[Assert\Length(min: 4, minMessage: 'Le prenom doit comporter au moins {{ limit }} caractères')]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, name: "motDePasse")]
    private ?string $motDePasse = null;

    #[ORM\Column(length: 255,name:"numeroTelephone")]
    #[Assert\NotBlank(message: 'Veuillez remplir tous les champs obligatoires')]
    #[Assert\Length(min: 8, minMessage: 'Le numéro doit comporter au moins {{ limit }} chiffres')]
    #[Assert\Regex(
        pattern: '/^[295]\d{7}$/',
        message: 'Le numéro doit commencer par 2, 9 ou 5 et être suivi de 7 chiffres.'
    )]
    private ?string $numeroTelephone = null;

    
    
#[ORM\Column(length: 255, name: "dateNaissance")]
    
    private ?string $dateNaissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'veuillez remplir tous les champs obligatoires')]
    private ?string $genre = null; 
    #[ORM\OneToMany(mappedBy: 'UseName', targetEntity: Reclamation::class)]
    private Collection $reclamations;
    #[ORM\OneToMany(mappedBy: 'UseName', targetEntity: Demande::class)]
    private Collection $demandes;



    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isVerified = true;

    
    public function getId(): ?int
    {
        return $this->id;
    }
    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
        $this->demandes = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

   

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = [];
        
        if($this->role == 'ADMIN'){
            $roles[] = 'ROLE_ADMIN';
        }elseif($this->role == 'CLIENT'){   
            $roles[] = 'ROLE_CLIENT';
        }elseif($this->role == 'GUIDE'){   
            $roles[] = 'ROLE_GUIDE';
        }
        // guarantee every user at least has ROLE_USER

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {

        if($this->role == 'ADMIN'){
            $roles[] = 'ROLE_ADMIN';
        }elseif($this->role == 'CLIENT'){   
            $roles[] = 'ROLE_CLIENT';
        }elseif($this->role == 'GUIDE'){   
            $roles[] = 'ROLE_GUIDE';
        }
        $this->roles = $roles;

        return $this;
    }


    /**
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see PasswordAuthenticatedUserInterface
     * 
     */
    public function getPassword(): ?string
    {
        return $this->motDePasse;    }

    /**
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public function getRole(): ?string
    {
        return $this->role;

    }

    
    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }



    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(?string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getNumeroTelephone(): ?string
    {
        return $this->numeroTelephone;
    }

    public function setNumeroTelephone(?string $numeroTelephone): static
    {
        $this->numeroTelephone = $numeroTelephone;

        return $this;
    }

    public function getDateNaissance(): ?string
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?string $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    public function getCartefideliteId(): ?int
    {
        return $this->cartefideliteId;
    }

    public function setCartefideliteId(?int $cartefideliteId): static
    {
        $this->cartefideliteId = $cartefideliteId;

        return $this;
    }

    public function getCartefidelite(): ?Cartefidelite
    {
        return $this->cartefidelite;
    }

    public function setCartefidelite(?Cartefidelite $cartefidelite): static
    {
        // unset the owning side of the relation if necessary
        if ($cartefidelite === null && $this->cartefidelite !== null) {
            $this->cartefidelite->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($cartefidelite !== null && $cartefidelite->getUser() !== $this) {
            $cartefidelite->setUser($this);
        }

        $this->cartefidelite = $cartefidelite;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setUseName($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getUseName() === $this) {
                $reclamation->setUseName(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): static
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setUseName($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getUseName() === $this) {
                $demande->setUseName(null);
            }
        }

        return $this;
    }

}
