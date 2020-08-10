<?php

namespace App\Entity;

use App\Repository\EdSummaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EdSummaryRepository::class)
 */
class EdSummary
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $ed;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="smallint")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="edSummaries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=County::class, inversedBy="edSummaries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $County;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $sortkey;

    /**
     * @ORM\OneToMany(targetEntity=Enumeration::class, mappedBy="ed")
     */
    private $enumerations;

    public function __construct()
    {
        $this->enumerations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEd(): ?string
    {
        return $this->ed;
    }

    public function setEd(string $ed): self
    {
        $this->ed = $ed;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

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

    public function getSortkey(): ?string
    {
        return $this->sortkey;
    }

    public function setSortkey(string $sortkey): self
    {
        $this->sortkey = $sortkey;

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
            $enumeration->setEd($this);
        }

        return $this;
    }

    public function removeEnumeration(Enumeration $enumeration): self
    {
        if ($this->enumerations->contains($enumeration)) {
            $this->enumerations->removeElement($enumeration);
            // set the owning side to null (unless already changed)
            if ($enumeration->getEd() === $this) {
                $enumeration->setEd(null);
            }
        }

        return $this;
    }
}
