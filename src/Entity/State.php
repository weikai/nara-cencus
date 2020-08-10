<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StateRepository::class)
 */
class State
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=CityState::class, mappedBy="state")
     */
    private $cityStates;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $Abbr;

    /**
     * @ORM\OneToMany(targetEntity=EdSummary::class, mappedBy="state")
     */
    private $edSummaries;

    /**
     * @ORM\OneToMany(targetEntity=MapImage::class, mappedBy="State")
     */
    private $mapImages;

    /**
     * @ORM\OneToMany(targetEntity=Enumeration::class, mappedBy="State")
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
            $cityState->setState($this);
        }

        return $this;
    }

    public function removeCityState(CityState $cityState): self
    {
        if ($this->cityStates->contains($cityState)) {
            $this->cityStates->removeElement($cityState);
            // set the owning side to null (unless already changed)
            if ($cityState->getState() === $this) {
                $cityState->setState(null);
            }
        }

        return $this;
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

    public function getAbbr(): ?string
    {
        return $this->Abbr;
    }

    public function setAbbr(string $Abbr): self
    {
        $this->Abbr = $Abbr;

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
            $edSummary->setState($this);
        }

        return $this;
    }

    public function removeEdSummary(EdSummary $edSummary): self
    {
        if ($this->edSummaries->contains($edSummary)) {
            $this->edSummaries->removeElement($edSummary);
            // set the owning side to null (unless already changed)
            if ($edSummary->getState() === $this) {
                $edSummary->setState(null);
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
            $mapImage->setState($this);
        }

        return $this;
    }

    public function removeMapImage(MapImage $mapImage): self
    {
        if ($this->mapImages->contains($mapImage)) {
            $this->mapImages->removeElement($mapImage);
            // set the owning side to null (unless already changed)
            if ($mapImage->getState() === $this) {
                $mapImage->setState(null);
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
            $enumeration->setState($this);
        }

        return $this;
    }

    public function removeEnumeration(Enumeration $enumeration): self
    {
        if ($this->enumerations->contains($enumeration)) {
            $this->enumerations->removeElement($enumeration);
            // set the owning side to null (unless already changed)
            if ($enumeration->getState() === $this) {
                $enumeration->setState(null);
            }
        }

        return $this;
    }
}
