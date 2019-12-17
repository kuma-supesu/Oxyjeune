<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Heure as Heure;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Planning
 * @ORM\Entity(repositoryClass="App\Repository\PlanningRepository")
 */
class Planning
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @Assert\Date()
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @ORM\Column(name="duree", type="integer")
     */
    private $duree;

    /**
     * @ORM\Column(name="iteration", type="integer", options={"default" : 0})
     */
    private $iteration;

    /**
     * @ORM\OneToMany(targetEntity="Heure", mappedBy="date", cascade={"persist", "remove"})
     */
    private $heures;

    public function __construct()
    {
        $this->heures= new ArrayCollection();
    }

    /**GETTER & SETTER**/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIteration(): ?int
    {
        return $this->iteration;
    }

    public function setIteration(int $iteration): self
    {
        $this->iteration = $iteration;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Set heure
     *
     * @param \App\Entity\Heure $heure
     *
     * @return heure
     */
    public function addHeure(Heure $heure = null)
    {
        if ($this->heure->contains($heure)) {
            $this->heures->add($heure);
        }

        $heure->setCommande($this);
        $this->heures->add($heure);
        return $this;
    }

    /**
     * Get heure
     *
     * @return \App\Entity\Heure
     */
    public function getHeures()
    {
        if ($this->heures->isEmpty()) {
            return [];
        }
        return $this->heures->toArray();
    }
}