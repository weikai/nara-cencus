<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Name;

    /**
     * @ORM\OneToMany(targetEntity=CityState::class, mappedBy="City")
     */
    private $cityStates;

    /**
     * @ORM\OneToMany(targetEntity=MapImage::class, mappedBy="City")
     */
    private $mapImages;

    /**
     * @ORM\OneToMany(targetEntity=Enumeration::class, mappedBy="city")
     */
    private $enumerations;

    public function __construct()
    {
        $this->cityStates = new ArrayCollection();
        $this->mapImages = new ArrayCollection();
        $this->enumerations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection|CityState[]
     */
    public function getCityStates(): Collection
    {
        return $this->cityStates;
    }

    public function addCityState(CityState $cityState): self
    {
        if (!$this->cityStates->contains($cityState)) {
            $this->cityStates[] = $cityState;
            $cityState->setCity($this);
        }

        return $this;
    }

    public function removeCityState(CityState $cityState): self
    {
        if ($this->cityStates->contains($cityState)) {
            $this->cityStates->removeElement($cityState);
            // set the owning side to null (unless already changed)
            if ($cityState->getCity() === $this) {
                $cityState->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MapImage[]
     */
    public function getMapImages(): Collection
    {
        return $this->mapImages;
    }

    public function addMapImage(MapImage $mapImage): self
    {
        if (!$this->mapImages->contains($mapImage)) {
            $this->mapImages[] = $mapImage;
            $mapImage->setCity($this);
        }

        return $this;
    }

    public function removeMapImage(MapImage $mapImage): self
    {
        if ($this->mapImages->contains($mapImage)) {
            $this->mapImages->removeElement($mapImage);
            // set the owning side to null (unless already changed)
            if ($mapImage->getCity() === $this) {
                $mapImage->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Enumeration[]
     */
    public function getEnumerations(): Collection
    {
        return $this->enumerations;
    }

    public function addEnumeration(Enumeration $enumeration): self
    {
        if (!$this->enumerations->contains($enumeration)) {
            $this->enumerations[] = $enumeration;
            $enumeration->setCity($this);
        }

        return $this;
    }

    public function removeEnumeration(Enumeration $enumeration): self
    {
        if ($this->enumerations->contains($enumeration)) {
            $this->enumerations->removeElement($enumeration);
            // set the owning side to null (unless already changed)
            if ($enumeration->getCity() === $this) {
                $enumeration->setCity(null);
            }
        }

        return $this;
    }
}
