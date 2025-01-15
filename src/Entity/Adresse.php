<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdresseRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:identite:list', 'read:adresse:item', 'read:competence:list', 'read:competence:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:identite:list', 'read:adresse:item', 'read:competence:list', 'read:competence:item'])]
    private ?string $rue = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read:identite:list', 'read:adresse:item', 'read:competence:list', 'read:competence:item'])]
    private ?int $appartement = null;

    #[ORM\ManyToOne(inversedBy: 'adresses')]
    private ?Ville $villes = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(mappedBy: 'adresse', targetEntity: User::class, orphanRemoval: false)] // Pas de cascade: remove
    private Collection $users;


    #[Groups(['read:identite:list', 'read:adresse:item', 'read:competence:list', 'read:competence:item'])]
    private ?string $ville = null;

    #[Groups(['read:identite:list', 'read:adresse:item', 'read:competence:list', 'read:competence:item'])]
    private ?string $pays = null;

    #[Groups(['read:identite:list', 'read:adresse:item', 'read:competence:list', 'read:competence:item'])]
    private ?int $codePostal = null;

    /**
     * @var Collection<int, user>
     */
    #[ORM\OneToMany(targetEntity: user::class, mappedBy: 'adresses')]
    private Collection $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getAppartement(): ?int
    {
        return $this->appartement;
    }

    public function setAppartement(?int $appartement): static
    {
        $this->appartement = $appartement;

        return $this;
    }

    public function getVilles(): ?Ville
    {
        return $this->villes;
    }

    public function setVilles(?Ville $villes): static
    {
        $this->villes = $villes;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->villes?->getLabel();
    }
    public function getPays(): ?string
    {
        return $this->villes?->getPays()?->getLabel();
    }
    public function getCodePostal(): ?int
    {
        return $this->villes?->getCodePostal();
    }

    /**
     * @return Collection<int, user>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(user $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setAdresses($this);
        }

        return $this;
    }

    public function removeUser(user $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAdresses() === $this) {
                $user->setAdresses(null);
            }
        }

        return $this;
    }
}
