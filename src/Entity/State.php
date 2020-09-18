<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StateRepository::class)
 * @ORM\Table(indexes={@ORM\Index(columns={"name"}, flags={"fulltext"})})
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
     * @ORM\Column(type="string", length=40, unique=true)
     */
    private $Name;

    /**
     * @ORM\Column(type="string", length=2, unique=true)
     */
    private $Abbr;

    /**
     * @ORM\OneToMany(targetEntity=EdSummary::class, mappedBy="state")
     */
    private $edSummaries;

    /**
     * @ORM\OneToMany(targetEntity=CensusImage::class, mappedBy="State")
     */
    private $censusImages;

    /**
     * @ORM\OneToMany(targetEntity=Enumeration::class, mappedBy="State")
     */
    private $enumerations;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="state")
     */
    private $addresses;

    public function __construct()
    {
        $this->cityStates = new ArrayCollection();
        $this->edSummaries = new ArrayCollection();
        $this->censusImages = new ArrayCollection();
        $this->enumerations = new ArrayCollection();
        $this->addresses = new ArrayCollection();
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
            $censusImage->setState($this);
        }

        return $this;
    }

    public function removeCensusImage(CensusImage $censusImage): self
    {
        if ($this->censusImages->contains($censusImage)) {
            $this->censusImages->removeElement($censusImage);
            // set the owning side to null (unless already changed)
            if ($censusImage->getState() === $this) {
                $censusImage->setState(null);
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

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setState($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getState() === $this) {
                $address->setState(null);
            }
        }

        return $this;
    }
}
