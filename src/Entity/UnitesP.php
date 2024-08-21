<?php

namespace App\Entity;

use App\Repository\UnitesPRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UnitesPRepository::class)]
class UnitesP
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['produit:read', 'stock:read','forStk:read'])]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['produit:read', 'stock:read','forStk:read'])]

    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['produit:read', 'stock:read','forStk:read'])]

    private ?int $valeur = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['stock:read','forStk:read'])]
    private ?int $suppr = null;

    #[ORM\ManyToOne(inversedBy: 'unite')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['stock:read'])]
    private ?Produits $produits = null;

    #[ORM\Column]
    #[Groups(['produit:read', 'stock:read','forStk:read'])]

    private ?int $prix = null;

    #[ORM\ManyToOne(inversedBy: 'unitesPs')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['stock:read', 'forStk:read'])]

    private ?Stock $stockDepot = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['stock:read','forStk:read'])]

    private ?Stock $stockMagasin = null;

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

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): static
    {
        $this->valeur = $valeur;

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

    public function getProduits(): ?Produits
    {
        return $this->produits;
    }

    public function setProduits(?Produits $produits): static
    {
        $this->produits = $produits;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStockDepot(): ?Stock
    {
        return $this->stockDepot;
    }

    public function setStockDepot(?Stock $stockDepot): static
    {
        $this->stockDepot = $stockDepot;

        return $this;
    }

    public function getStockMagasin(): ?Stock
    {
        return $this->stockMagasin;
    }

    public function setStockMagasin(?Stock $stockMagasin): static
    {
        $this->stockMagasin = $stockMagasin;

        return $this;
    }
}
