<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OffresRepository;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OffresRepository::class)]
class Offres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:offre:list:user', 'read:competence:item'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    #[ORM\JoinColumn(nullable: false)]

    private ?competences $competence = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:offre:list:user', 'read:competence:item'])]
    private ?string $libelle = null;

    #[ORM\OneToOne(mappedBy: 'offre', cascade: ['persist', 'remove'])]
    private ?Notification $yes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCompetence(): ?competences
    {
        return $this->competence;
    }

    public function setCompetence(?competences $competence): static
    {
        $this->competence = $competence;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getYes(): ?Notification
    {
        return $this->yes;
    }

    public function setYes(?Notification $yes): static
    {
        // unset the owning side of the relation if necessary
        if ($yes === null && $this->yes !== null) {
            $this->yes->setOffre(null);
        }

        // set the owning side of the relation if necessary
        if ($yes !== null && $yes->getOffre() !== $this) {
            $yes->setOffre($this);
        }

        $this->yes = $yes;

        return $this;
    }
}
