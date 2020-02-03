<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\TableauPaiement as TableauPaiement;
use App\Entity\Tableau as Tableau;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TableauLigneRepository")
 * @ORM\Table(name="tableau_ligne")
 */
class TableauLigne
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
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $paiementXFois;

    /**
     * @ORM\Column(type="boolean")
     */
    private $payee;

    /**
     * @ORM\OneToMany(targetEntity="TableauPaiement", mappedBy="tableauLigne", cascade={"persist", "remove"})
     */
    private $tableauPaiements;

    /**
     * @ORM\ManyToOne(targetEntity="Tableau", inversedBy="tableauLignes")
     * @JoinColumn(name="tableau_id", referencedColumnName="id")
     */
    private $tableau;

    public function __construct()
    {
        $this->tableauPaiements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

     public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

     public function getPaiementXFois(): ?int
    {
        return $this->paiementXFois;
    }

    public function setPaiementXFois(int $paiementXFois): self
    {
        $this->paiementXFois = $paiementXFois;

        return $this;
    }

    public function getPayee(): ?bool
    {
        return $this->payee;
    }

    public function setPayee(bool $payee): self
    {
        $this->payee = $payee;

        return $this;
    }

    public function getTableauPaiements(): ?Collection
    {
        return $this->tableauPaiements;
    }

    public function addTableauPaiement(TableauPaiement $tableauPaiement): self
    {
        $tableauPaiement->setTableauLigne($this);
        $this->tableauPaiements->add($tableauPaiement);
        return $this;
    }

    public function removeTableauPaiement(TableauPaiement $tableauPaiement): self
    {
        if ($this->tableauPaiements->contains($tableauPaiement)) {
            $this->tableauPaiements->removeElement($tableauPaiement);
        }
        return $this->tableauPaiements;
    }

    public function getTableau(): ?Tableau
    {
        return $this->tableau;
    }

    public function setTableau(Tableau $tableau): self
    {
        $this->tableau = $tableau;

        return $this;
    }
}