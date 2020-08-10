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
     * @ORM\OneToMany(targetEntity=MapImage::class, mappedBy="enum")
     */
    private $mapImages;

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
        $this->mapImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $mapImage->setEnum($this);
        }

        return $this;
    }

    public function removeMapImage(MapImage $mapImage): self
    {
        if ($this->mapImages->contains($mapImage)) {
            $this->mapImages->removeElement($mapImage);
            // set the owning side to null (unless already changed)
            if ($mapImage->getEnum() === $this) {
                $mapImage->setEnum(null);
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
