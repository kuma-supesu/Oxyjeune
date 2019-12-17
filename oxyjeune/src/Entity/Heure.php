<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Plannig as Planning;

/**
 * Heure
 * @ORM\Entity
 * @ORM\Table(name="heure")
 */
class Heure
{
    /**
     * @ORM\ManyToOne(targetEntity="Planning", inversedBy="date")
     */
    private $planning;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="heure", type="time")
     */
    private $heure;

    /**
     * @ORM\Column(name="noms", type="array", nullable=TRUE)
     */
    private $noms;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getNoms(): ?array
    {
        return $this->noms;
    }

    public function setNoms(?array $noms): self
    {
        $this->noms = $noms;

        return $this;
    }

    /**
     * Set planning
     *
     * @param \App\Entity\Planning $planning
     *
     * @return Heure
     */
    public function setPlanning(Planning $planning = null)
    {
        $this->planning = $planning;

        return $this;
    }

    /**
     * Get planning
     *
     * @return \App\Entity\Planning
     */
    public function getPlanning()
    {
        return $this->planning;
    }


}