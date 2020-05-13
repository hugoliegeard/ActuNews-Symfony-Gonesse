<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message="N'oubliez pas de saisir votre prénom")
     * @Assert\Length(max="80", maxMessage="Attention, Pas plus de {{limit}} caractères.")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\Length(max="80", maxMessage="Attention, Pas plus de {{limit}} caractères.")
     */
    private $lastname;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="N'oubliez pas de saisir votre message")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Email(message="Vérifier le format de votre email")
     * @Assert\NotBlank(message="Vous devez saisir votre email")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Regex(pattern="[0-9]{10}/")
     */
    private $telephone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
