<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Heure as Heure;
use App\Entity\Planning as Planning;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JourneeRepository")
 * @ORM\Table(name="journee")
 */
class Journee
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $dureeHeure;

    /**
     * @ORM\Column(type="integer")
     */
    private $dureeMinute;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombrePersonnes;

    /**
     * @ORM\OneToMany(targetEntity="Heure", mappedBy="journee", cascade={"persist", "remove"})
     */
    private $heures;

    /**
     * @ORM\ManyToOne(targetEntity="Planning", inversedBy="journees")
     * @JoinColumn(name="planning_id", referencedColumnName="id")
     */
    private $planning;

    public function __construct()
    {
        $this->heures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNombrePersonnes(): ?int
    {
        return $this->nombrePersonnes;
    }

    public function setNombrePersonnes(int $nombrePersonnes): self
    {
        $this->nombrePersonnes = $nombrePersonnes;

        return $this;
    }

    public function getHeures(): Collection
    {
        return $this->heures;
    }

    public function addHeure(Heure $heure): self
    {
        $heure->setJournee($this);
        $this->heures->add($heure);
        return $this;
    }

    public function removeHeure(Heure $heure): self
    {
        if ($this->heures->contains($heure)) {
            $this->heures->removeElement($heure);
        }
        return $this->heures;
    }

    public function getPlanning(): ?Planning
    {
        return $this->planning;
    }

    public function setPlanning(?Planning $planning): self
    {
        $this->planning = $planning;

        return $this;
    }

    public function getDureeMinute()
    {
        return $this->dureeMinute;
    }

    public function setDureeMinute($dureeMinute): void
    {
        $this->dureeMinute = $dureeMinute;
    }

    public function getDureeHeure()
    {
        return $this->dureeHeure;
    }

    public function setDureeHeure($dureeHeure): void
    {
        $this->dureeHeure = $dureeHeure;
    }

}
