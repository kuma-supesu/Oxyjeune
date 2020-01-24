<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\TableauLigne as TableauLigne;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TableauRepository")
 * @ORM\Table(name="tableau")
 */
class Tableau
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
    private $annee;

    /**
     * @ORM\Column(type="string")
     */
    private $classeur;

    /**
     * @ORM\OneToMany(targetEntity="TableauLigne", mappedBy="tableau", cascade={"persist", "remove"})
     */
    private $tableauLignes;

    public function __construct()
    {
        $this->tableauLignes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?\DateTimeInterface
    {
        return $this->annee;
    }

    public function setAnnee(\DateTimeInterface $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getClasseur(): ?string
    {
        return $this->classeur;
    }

    public function setClasseur(string $classeur): self
    {
        $this->classeur = $classeur;

        return $this;
    }

    public function getTableauLignes(): ?Collection
    {
        return $this->tableauLignes;
    }

    public function addTableauLigne(TableauLigne $tableauLigne): self
    {
        $tableauLigne->setTableau($this);
        $this->tableauLignes->add($tableauLigne);
        return $this;
    }

    public function removeTableauLigne(TableauLigne $tableauLigne): self
    {
        if ($this->tableauLignes->contains($tableauLigne)) {
            $this->tableauLignes->removeElement($tableauLigne);
        }
        return $this->tableauLignes;
    }
}