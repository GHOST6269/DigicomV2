<?php

namespace App\Entity;

use App\Repository\MouvementStockRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MouvementStockRepository::class)]
class MouvementStock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementStocks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['produit:read'])]

    private ?Produits $produits = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementStocks')]
    #[Groups(['produit:read'])]

    private ?Depot $emplacementoriginDepot = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementStocks')]
    #[Groups(['produit:read'])]

    private ?Magasin $emplacementoriginMagasin = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementStocksDestination')]
    #[Groups(['produit:read'])]

    private ?Depot $destinationDepot = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementStocksDestination')]
    #[Groups(['produit:read'])]

    private ?Magasin $destinationMagasin = null;

    #[ORM\Column]
    #[Groups(['produit:read'])]

    private ?int $qte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['produit:read'])]

    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['produit:read'])]

    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(length: 255)]
    #[Groups(['produit:read'])]

    private ?string $description = null;

    #[ORM\ManyToOne]
    #[Groups(['produit:read'])]

    private ?UnitesP $uniteP = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['produit:read'])]

    private ?int $prixAchat = null;

    #[ORM\ManyToOne]
    #[Groups(['produit:read'])]

    private ?Fournisseur $fournisseur = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmplacementoriginDepot(): ?Depot
    {
        return $this->emplacementoriginDepot;
    }

    public function setEmplacementoriginDepot(?Depot $emplacementoriginDepot): static
    {
        $this->emplacementoriginDepot = $emplacementoriginDepot;

        return $this;
    }

    public function getEmplacementoriginMagasin(): ?Magasin
    {
        return $this->emplacementoriginMagasin;
    }

    public function setEmplacementoriginMagasin(?Magasin $emplacementoriginMagasin): static
    {
        $this->emplacementoriginMagasin = $emplacementoriginMagasin;

        return $this;
    }

    public function getDestinationDepot(): ?Depot
    {
        return $this->destinationDepot;
    }

    public function setDestinationDepot(?Depot $destinationDepot): static
    {
        $this->destinationDepot = $destinationDepot;

        return $this;
    }

    public function getDestinationMagasin(): ?Magasin
    {
        return $this->destinationMagasin;
    }

    public function setDestinationMagasin(?Magasin $destinationMagasin): static
    {
        $this->destinationMagasin = $destinationMagasin;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUniteP(): ?UnitesP
    {
        return $this->uniteP;
    }

    public function setUniteP(?UnitesP $uniteP): static
    {
        $this->uniteP = $uniteP;

        return $this;
    }

    public function getPrixAchat(): ?int
    {
        return $this->prixAchat;
    }

    public function setPrixAchat(?int $prixAchat): static
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }
}
