<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Journee as Journee;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HeureRepository")
 * @ORM\Table(name="heure")
 */
class Heure
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $PlageHoraire;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $noms = [];

    /**
     * @ORM\ManyToOne(targetEntity="Journee", inversedBy="heures")
     * @JoinColumn(name="journee_id", referencedColumnName="id")
     */
    private $journee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlageHoraire(): ?\DateTimeInterface
    {
        return $this->PlageHoraire;
    }

    public function setPlageHoraire(\DateTimeInterface $PlageHoraire): self
    {
        $this->PlageHoraire = $PlageHoraire;

        return $this;
    }

    public function getNoms(): ?array
    {
        return $this->noms;
    }

    public function setNoms(array $noms): self
    {
        $this->noms = $noms;

        return $this;
    }

    public function getJournee(): ?Journee
    {
        return $this->journee;
    }

    public function setJournee(?Journee $journee): self
    {
        $this->journee = $journee;

        return $this;
    }

}
