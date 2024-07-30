<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('client:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('client:read')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups('client:read')]
    private ?string $contact = null;

    #[ORM\Column]
    #[Groups('client:read')]
    private ?int $compte = null;

    #[ORM\Column]
    private ?int $suppr = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getCompte(): ?int
    {
        return $this->compte;
    }

    public function setCompte(int $compte): static
    {
        $this->compte = $compte;

        return $this;
    }

    public function getSuppr(): ?int
    {
        return $this->suppr;
    }

    public function setSuppr(int $suppr): static
    {
        $this->suppr = $suppr;

        return $this;
    }
}
