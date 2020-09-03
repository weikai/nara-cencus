<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class) 
 * @ORM\Table(indexes={@ORM\Index(columns={"name"}, flags={"fulltext"})})
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
     * @ORM\OneToMany(targetEntity=CensusImage::class, mappedBy="City")
     */
    private $censusImages;

    /**
     * @ORM\OneToMany(targetEntity=Enumeration::class, mappedBy="city")
     */
    private $enumerations;

    public function __construct()
    {
        $this->cityStates = new ArrayCollection();
        $this->censusImages = new ArrayCollection();
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
     * @return Collection|CensusImage[]
     */
    public function getCensusImages(): Collection
    {
        return $this->censusImages;
    }

    public function addCensusImage(CensusImage $censusImage): self
    {
        if (!$this->censusImages->contains($censusImage)) {
            $this->censusImages[] = $censusImage;
            $censusImage->setCity($this);
        }

        return $this;
    }

    public function removeCensusImage(CensusImage $censusImage): self
    {
        if ($this->censusImages->contains($censusImage)) {
            $this->censusImages->removeElement($censusImage);
            // set the owning side to null (unless already changed)
            if ($censusImage->getCity() === $this) {
                $censusImage->setCity(null);
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
