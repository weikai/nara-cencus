<?php

namespace App\Entity;

use App\Repository\CountyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountyRepository::class)
 * @ORM\Table(indexes={@ORM\Index(columns={"name"}, flags={"fulltext"})})
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
    private $Name;

    /**
     * @ORM\OneToMany(targetEntity=EdSummary::class, mappedBy="County")
     */
    private $edSummaries;

    /**
     * @ORM\OneToMany(targetEntity=CensusImage::class, mappedBy="County")
     */
    private $censusImages;

    /**
     * @ORM\OneToMany(targetEntity=Enumeration::class, mappedBy="County")
     */
    private $enumerations;

    public function __construct()
    {
        $this->cityStates = new ArrayCollection();
        $this->edSummaries = new ArrayCollection();
        $this->censusImages = new ArrayCollection();
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
        return $this->Name;
    }

    public function setName(string $name): self
    {
        $this->Name = $name;

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
            $censusImage->setCounty($this);
        }

        return $this;
    }

    public function removeCensusImage(CensusImage $censusImage): self
    {
        if ($this->censusImages->contains($censusImage)) {
            $this->censusImages->removeElement($censusImage);
            // set the owning side to null (unless already changed)
            if ($censusImage->getCounty() === $this) {
                $censusImage->setCounty(null);
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
