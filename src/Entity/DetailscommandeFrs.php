<?php

namespace App\Entity;

use App\Repository\DetailscommandeFrsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailscommandeFrsRepository::class)]
class DetailscommandeFrs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'detailscommandeFrs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CommandeFournisseur $commande = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produits $produit = null;

    #[ORM\Column]
    private ?int $qte = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?UnitesP $unite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): ?CommandeFournisseur
    {
        return $this->commande;
    }

    public function setCommande(?CommandeFournisseur $commande): static
    {
        $this->commande = $commande;

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

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): static
    {
        $this->qte = $qte;

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

    public function getUnite(): ?UnitesP
    {
        return $this->unite;
    }

    public function setUnite(?UnitesP $unite): static
    {
        $this->unite = $unite;

        return $this;
    }
}
