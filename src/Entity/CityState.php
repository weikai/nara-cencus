<?php

namespace App\Entity;

use App\Repository\CityStateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityStateRepository::class)
 */
class CityState
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="cityStates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=County::class, inversedBy="cityStates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $County;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="cityStates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $City;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCity(): ?City
    {
        return $this->City;
    }

    public function setCity(?City $City): self
    {
        $this->City = $City;

        return $this;
    }
}
