<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Journee as Journee;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $noms;

    /**
     * @ORM\ManyToOne(targetEntity="Journee", inversedBy="heures")
     */
    private $journee;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPlageHoraire(): ?\DateTimeInterface
    {
        return $this->PlageHoraire;
    }

    /**
     * @param \DateTimeInterface $PlageHoraire
     * @return $this
     */
    public function setPlageHoraire(\DateTimeInterface $PlageHoraire): self
    {
        $this->PlageHoraire = $PlageHoraire;

        return $this;
    }

    public function getNoms(): ?string
    {
        return $this->noms;
    }

    public function setNoms(string $noms): self
    {
        $this->noms = $noms;

        return $this;
    }

    /**
     * @return Journee
     */
    public function getJournee(): ?Journee
    {
        return $this->journee;
    }

    /**
     * @param Journee $journee
     * @return $this
     */
    public function setJournee(?Journee $journee): self
    {
        $this->journee = $journee;

        return $this;
    }

}
