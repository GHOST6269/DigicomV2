<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['produit:read', 'stock:read'])]

    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['produit:read', 'stock:read'])]
    private ?Produits $produit = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    #[Groups(['produit:read'])]

    private ?Magasin $magasin = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    #[Groups(['produit:read', 'stock:read'])]

    private ?Depot $depot = null;

    #[ORM\Column]
    #[Groups(['produit:read', 'stock:read'])]
    private ?int $qte = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['produit:read'])]

    private ?UnitesP $unite = null;
    /**
     * @var Collection<int, UnitesP>
     */
    #[ORM\OneToMany(targetEntity: UnitesP::class, mappedBy: 'stockDepot')]
    private Collection $unitesPs;

    public function __construct()
    {
        $this->unitesPs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMagasin(): ?Magasin
    {
        return $this->magasin;
    }

    public function setMagasin(?Magasin $magasin): static
    {
        $this->magasin = $magasin;

        return $this;
    }

    public function getDepot(): ?Depot
    {
        return $this->depot;
    }

    public function setDepot(?Depot $depot): static
    {
        $this->depot = $depot;

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

    public function getUnite(): ?UnitesP
    {
        return $this->unite;
    }

    public function setUnite(?UnitesP $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    /**
     * @return Collection<int, UnitesP>
     */
    public function getUnitesPs(): Collection
    {
        return $this->unitesPs;
    }

    public function addUnitesP(UnitesP $unitesP): static
    {
        if (!$this->unitesPs->contains($unitesP)) {
            $this->unitesPs->add($unitesP);
            $unitesP->setStockDepot($this);
        }

        return $this;
    }

    public function removeUnitesP(UnitesP $unitesP): static
    {
        if ($this->unitesPs->removeElement($unitesP)) {
            if ($unitesP->getStockDepot() === $this) {
                $unitesP->setStockDepot(null);
            }
        }

        return $this;
    }
}
