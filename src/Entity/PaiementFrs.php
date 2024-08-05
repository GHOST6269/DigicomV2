<?php

namespace App\Entity;

use App\Repository\PaiementFrsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementFrsRepository::class)]
class PaiementFrs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Achat $achat = null;

    #[ORM\Column]
    private ?int $montantP = null;

    #[ORM\Column]
    private ?int $reste = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAchat(): ?Achat
    {
        return $this->achat;
    }

    public function setAchat(?Achat $achat): static
    {
        $this->achat = $achat;

        return $this;
    }

    public function getMontantP(): ?int
    {
        return $this->montantP;
    }

    public function setMontantP(int $montantP): static
    {
        $this->montantP = $montantP;

        return $this;
    }

    public function getReste(): ?int
    {
        return $this->reste;
    }

    public function setReste(int $reste): static
    {
        $this->reste = $reste;

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
