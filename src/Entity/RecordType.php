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
     * @ORM\OneToMany(targetEntity=MapImage::class, mappedBy="type")
     */
    private $mapImages;

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
            $mapImage->setType($this);
        }

        return $this;
    }

    public function removeMapImage(MapImage $mapImage): self
    {
        if ($this->mapImages->contains($mapImage)) {
            $this->mapImages->removeElement($mapImage);
            // set the owning side to null (unless already changed)
            if ($mapImage->getType() === $this) {
                $mapImage->setType(null);
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
