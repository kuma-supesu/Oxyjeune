<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Journee as Journee;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlanningRepository")
 * @ORM\Table(name="planning")
 */
class Planning
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $event;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $debut;

    /**
     * @ORM\OneToMany(targetEntity="Journee", mappedBy="planning", cascade={"persist", "remove"})
     */
    private $journees;

    public function __construct()
    {
        $this->journees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJournees(): Collection
    {
        return $this->journees;
    }

    public function addJournee(Journee $journee): self
    {
        $journee->setPlanning($this);
        $this->journees->add($journee);
        return $this;
    }

    public function removeJournee(Journee $journee): self
    {
        if ($this->journees->contains($journee)) {
            $this->journees->removeElement($journee);
        }
        return $this->journees;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function setEvent($event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

}