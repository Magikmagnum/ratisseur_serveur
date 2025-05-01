<?php

namespace App\Entity;

use App\Repository\CoordsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CoordsRepository::class)]
class Coords
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:location:list', 'read:location:item'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['read:location:list', 'read:location:item'])]
    private ?float $latitude = null;

    #[ORM\Column]
    #[Groups(['read:location:list', 'read:location:item'])]
    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:location:list', 'read:location:item'])]
    private ?float $altitude = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:location:list', 'read:location:item'])]
    private ?float $accuracy = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:location:list', 'read:location:item'])]
    private ?float $altitudeAccuracy = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:location:list', 'read:location:item'])]
    private ?float $heading = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read:location:list', 'read:location:item'])]
    private ?float $speed = null;

    /**
     * @var Collection<int, Localisation>
     */
    #[ORM\OneToMany(targetEntity: Localisation::class, mappedBy: 'coords')]
    private Collection $localisations;

    public function __construct()
    {
        $this->localisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }

    public function setAltitude(?float $altitude): static
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function getAccuracy(): ?float
    {
        return $this->accuracy;
    }

    public function setAccuracy(?float $accuracy): static
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function getAltitudeAccuracy(): ?float
    {
        return $this->altitudeAccuracy;
    }

    public function setAltitudeAccuracy(?float $altitudeAccuracy): static
    {
        $this->altitudeAccuracy = $altitudeAccuracy;

        return $this;
    }

    public function getHeading(): ?float
    {
        return $this->heading;
    }

    public function setHeading(?float $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function getSpeed(): ?float
    {
        return $this->speed;
    }

    public function setSpeed(?float $speed): static
    {
        $this->speed = $speed;

        return $this;
    }

    /**
     * @return Collection<int, Localisation>
     */
    public function getLocalisations(): Collection
    {
        return $this->localisations;
    }

    public function addLocalisation(Localisation $localisation): static
    {
        if (!$this->localisations->contains($localisation)) {
            $this->localisations->add($localisation);
            $localisation->setCoord($this);
        }

        return $this;
    }

    public function removeLocalisation(Localisation $localisation): static
    {
        if ($this->localisations->removeElement($localisation)) {
            // set the owning side to null (unless already changed)
            if ($localisation->getCoord() === $this) {
                $localisation->setCoord(null);
            }
        }

        return $this;
    }
}
