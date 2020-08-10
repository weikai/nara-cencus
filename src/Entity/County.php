<?php

namespace App\Entity;

use App\Repository\CountyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountyRepository::class)
 */
class County
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=CityState::class, mappedBy="County")
     */
    private $cityStates;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=EdSummary::class, mappedBy="County")
     */
    private $edSummaries;

    /**
     * @ORM\OneToMany(targetEntity=MapImage::class, mappedBy="County")
     */
    private $mapImages;

    /**
     * @ORM\OneToMany(targetEntity=Enumeration::class, mappedBy="County")
     */
    private $enumerations;

    public function __construct()
    {
        $this->cityStates = new ArrayCollection();
        $this->edSummaries = new ArrayCollection();
        $this->mapImages = new ArrayCollection();
        $this->enumerations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $cityState->setCounty($this);
        }

        return $this;
    }

    public function removeCityState(CityState $cityState): self
    {
        if ($this->cityStates->contains($cityState)) {
            $this->cityStates->removeElement($cityState);
            // set the owning side to null (unless already changed)
            if ($cityState->getCounty() === $this) {
                $cityState->setCounty(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|EdSummary[]
     */
    public function getEdSummaries(): Collection
    {
        return $this->edSummaries;
    }

    public function addEdSummary(EdSummary $edSummary): self
    {
        if (!$this->edSummaries->contains($edSummary)) {
            $this->edSummaries[] = $edSummary;
            $edSummary->setCounty($this);
        }

        return $this;
    }

    public function removeEdSummary(EdSummary $edSummary): self
    {
        if ($this->edSummaries->contains($edSummary)) {
            $this->edSummaries->removeElement($edSummary);
            // set the owning side to null (unless already changed)
            if ($edSummary->getCounty() === $this) {
                $edSummary->setCounty(null);
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
            $mapImage->setCounty($this);
        }

        return $this;
    }

    public function removeMapImage(MapImage $mapImage): self
    {
        if ($this->mapImages->contains($mapImage)) {
            $this->mapImages->removeElement($mapImage);
            // set the owning side to null (unless already changed)
            if ($mapImage->getCounty() === $this) {
                $mapImage->setCounty(null);
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
            $enumeration->setCounty($this);
        }

        return $this;
    }

    public function removeEnumeration(Enumeration $enumeration): self
    {
        if ($this->enumerations->contains($enumeration)) {
            $this->enumerations->removeElement($enumeration);
            // set the owning side to null (unless already changed)
            if ($enumeration->getCounty() === $this) {
                $enumeration->setCounty(null);
            }
        }

        return $this;
    }
}
