<?php

namespace App\Entity;

use App\Repository\EdSummaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EdSummaryRepository::class)
 * @ORM\Table(indexes={@ORM\Index(columns={"ed","description","statename","stateabbr","countyname","cityname"}, flags={"fulltext"})})
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
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $ed;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    private $description;

    
    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="edSummaries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=County::class, inversedBy="edSummaries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $county;

    
    /**
     * @ORM\OneToMany(targetEntity=Enumeration::class, mappedBy="ed")
     */
    private $enumerations;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $statename;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $stateabbr;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $countyname;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $cityname;

    /**
     * @ORM\Column(type="smallint")
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $sortkey;
    

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
        return $this->county;
    }

    public function setCounty(?County $county): self
    {
        $this->county = $county;

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

    public function getStatename(): ?string
    {
        return $this->statename;
    }

    public function setStatename(string $statename): self
    {
        $this->statename = $statename;

        return $this;
    }

    public function getCountyname(): ?string
    {
        return $this->countyname;
    }

    public function setCountyname(string $countyname): self
    {
        $this->countyname = $countyname;

        return $this;
    }

    public function getCityname(): ?string
    {
        return $this->cityname;
    }

    public function setCityname(?string $cityname): self
    {
        $this->cityname = $cityname;

        return $this;
    }

    public function getStateabbr(): ?string
    {
        return $this->stateabbr;
    }

    public function setStateabbr(string $stateabbr): self
    {
        $this->stateabbr = $stateabbr;

        return $this;
    }
}
