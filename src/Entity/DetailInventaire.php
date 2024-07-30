<?php

namespace App\Entity;

use App\Repository\DetailInventaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailInventaireRepository::class)]
class DetailInventaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'detailInventaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?inventaire $inventaire = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produits $produit = null;

    #[ORM\Column]
    private ?int $qtePhysique = null;

    #[ORM\Column]
    private ?int $qteTheorique = null;

    #[ORM\Column]
    private ?int $difference = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInventaire(): ?inventaire
    {
        return $this->inventaire;
    }

    public function setInventaire(?inventaire $inventaire): static
    {
        $this->inventaire = $inventaire;

        return $this;
    }

    public function getProduit(): ?Produits
    {
        return $this->produit;
    }

    public function setProduit(?Produits $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQtePhysique(): ?int
    {
        return $this->qtePhysique;
    }

    public function setQtePhysique(int $qtePhysique): static
    {
        $this->qtePhysique = $qtePhysique;

        return $this;
    }

    public function getQteTheorique(): ?int
    {
        return $this->qteTheorique;
    }

    public function setQteTheorique(int $qteTheorique): static
    {
        $this->qteTheorique = $qteTheorique;

        return $this;
    }

    public function getDifference(): ?int
    {
        return $this->difference;
    }

    public function setDifference(int $difference): static
    {
        $this->difference = $difference;

        return $this;
    }
}
