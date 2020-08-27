<?php

namespace App\Entity;

use App\Repository\EnumerationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EnumerationRepository::class)
 */
class Enumeration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=CensusImage::class, mappedBy="enum")
     */
    private $censusImages;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="enumerations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $State;

    /**
     * @ORM\ManyToOne(targetEntity=County::class, inversedBy="enumerations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $County;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="enumerations")
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=EdSummary::class, inversedBy="enumerations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ed;

    public function __construct()
    {
        $this->censusImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $censusImage->setEnum($this);
        }

        return $this;
    }

    public function removeCensusImage(CensusImage $censusImage): self
    {
        if ($this->censusImages->contains($censusImage)) {
            $this->censusImages->removeElement($censusImage);
            // set the owning side to null (unless already changed)
            if ($censusImage->getEnum() === $this) {
                $censusImage->setEnum(null);
            }
        }

        return $this;
    }

    public function getState(): ?State
    {
        return $this->State;
    }

    public function setState(?State $State): self
    {
        $this->State = $State;

        return $this;
    }

    public function getCounty(): ?County
    {
        return $this->County;
    }

    public function setCounty(?County $County): self
    {
        $this->County = $County;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getEd(): ?EdSummary
    {
        return $this->ed;
    }

    public function setEd(?EdSummary $ed): self
    {
        $this->ed = $ed;

        return $this;
    }
}
