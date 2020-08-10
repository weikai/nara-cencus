<?php

namespace App\Entity;

use App\Repository\MapImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MapImageRepository::class)
 */
class MapImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=RecordType::class, inversedBy="mapImages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="mapImages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $State;

    /**
     * @ORM\ManyToOne(targetEntity=County::class, inversedBy="mapImages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $County;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="mapImages")
     */
    private $City;

    /**
     * @ORM\ManyToOne(targetEntity=Enumeration::class, inversedBy="mapImages")
     */
    private $enum;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $publication;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $rollnum;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $imgseq;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $filename;

    /**
     * @ORM\Column(type="smallint")
     */
    private $year;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?RecordType
    {
        return $this->type;
    }

    public function setType(?RecordType $type): self
    {
        $this->type = $type;

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
        return $this->City;
    }

    public function setCity(?City $City): self
    {
        $this->City = $City;

        return $this;
    }

    public function getEnum(): ?Enumeration
    {
        return $this->enum;
    }

    public function setEnum(?Enumeration $enum): self
    {
        $this->enum = $enum;

        return $this;
    }

    public function getPublication(): ?string
    {
        return $this->publication;
    }

    public function setPublication(string $publication): self
    {
        $this->publication = $publication;

        return $this;
    }

    public function getRollnum(): ?string
    {
        return $this->rollnum;
    }

    public function setRollnum(string $rollnum): self
    {
        $this->rollnum = $rollnum;

        return $this;
    }

    public function getImgseq(): ?string
    {
        return $this->imgseq;
    }

    public function setImgseq(string $imgseq): self
    {
        $this->imgseq = $imgseq;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

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
}
