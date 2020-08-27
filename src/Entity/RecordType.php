<?php

namespace App\Entity;

use App\Repository\RecordTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecordTypeRepository::class)
 */
class RecordType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=CensusImage::class, mappedBy="type")
     */
    private $censusImages;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $label;

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
            $censusImage->setType($this);
        }

        return $this;
    }

    public function removeCensusImage(CensusImage $censusImage): self
    {
        if ($this->censusImages->contains($censusImage)) {
            $this->censusImages->removeElement($censusImage);
            // set the owning side to null (unless already changed)
            if ($censusImage->getType() === $this) {
                $censusImage->setType(null);
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }
}
