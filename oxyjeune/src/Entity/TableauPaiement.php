<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\TableauLigne as TableauLigne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TableauPaiementRepository")
 * @ORM\Table(name="tableau_paiement")
 */
class TableauPaiement
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
    private $dateVersement;

    /**
     * @ORM\Column(type="string")
     */
    private $moyenPaiement;

    /**
     * @ORM\Column(type="integer")
     */
    private $sommeVersement;

    /**
     * @ORM\ManyToOne(targetEntity="TableauLigne", inversedBy="tableauPaiements")
     * @JoinColumn(name="tableau_ligne_id", referencedColumnName="id")
     */
    private $tableauLigne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateVersement(): ?DateTimeInterface
    {
        return $this->dateVersement;
    }

    public function setDateVersement(DateTimeInterface $dateVersement): self
    {
        $this->dateVersement = $dateVersement;

        return $this;
    }

    public function getMoyenPaiement(): ?string
    {
        return $this->moyenPaiement;
    }

    public function setMoyenPaiement(string $moyenPaiement): self
    {
        $this->moyenPaiement = $moyenPaiement;

        return $this;
    }

    public function getSommeVersement(): ?int
    {
        return $this->sommeVersement;
    }

    public function setSommeVersement(int $sommeVersement): self
    {
        $this->sommeVersement = $sommeVersement;

        return $this;
    }

    public function getTableauLigne(): ?TableauLigne
    {
        return $this->tableauLigne;
    }

    public function setTableauLigne(TableauLigne $tableauLigne): self
    {
        $this->tableauLigne = $tableauLigne;

        return $this;
    }

}