<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "L'e-mail '{{ value }}' n'est pas un e-mail valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Length(
     *      min = 8,
     *      max = 25,
     *      minMessage = "Votre mot de passe doit faire au minimum {{ limit }} characteres",
     *      maxMessage = "Votre mot de passe doit faire au maximum {{ limit }} characteres"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string")
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Heure")
     */
    private $heures;

    public function __construct()
    {
        $this->heures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setRole($role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getRoles(): array
    {
        $roles[] = $this->role;

        return array_unique($roles);
    }

    public function getSalt()
    {

    }

    public function eraseCredentials()
    {

    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet($nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Heure[]
     */
    public function getHeures(): Collection
    {
        return $this->heures;
    }

    public function addHeure(Heure $heure): self
    {
        if (!$this->heures->contains($heure)) {
            $this->heures[] = $heure;
            $heure->addUser($this);
        }

        return $this;
    }

    public function removeHeure(Heure $heure): self
    {
        if ($this->heures->contains($heure)) {
            $this->heures->removeElement($heure);
            $heure->removeUser($this);
        }

        return $this;
    }

    public function getToken(): ?int
    {
        return $this->token;
    }

    public function setToken($token): self
    {
        $this->token = $token;

        return $this;
    }

}