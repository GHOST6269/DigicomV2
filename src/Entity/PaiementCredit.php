<?php

namespace App\Entity;

use App\Repository\PaiementCreditRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaiementCreditRepository::class)]
class PaiementCredit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('client:read')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('client:read')]
    private ?Vente $vente = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('client:read')]
    private ?Client $client = null;

    #[ORM\Column]
    #[Groups('client:read')]
    private ?int $montantPaye = null;

    #[ORM\Column]
    #[Groups('client:read')]
    private ?int $resteAPaye = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('client:read')]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVente(): ?Vente
    {
        return $this->vente;
    }

    public function setVente(?Vente $vente): static
    {
        $this->vente = $vente;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getMontantPaye(): ?int
    {
        return $this->montantPaye;
    }

    public function setMontantPaye(int $montantPaye): static
    {
        $this->montantPaye = $montantPaye;

        return $this;
    }

    public function getResteAPaye(): ?int
    {
        return $this->resteAPaye;
    }

    public function setResteAPaye(int $resteAPaye): static
    {
        $this->resteAPaye = $resteAPaye;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
