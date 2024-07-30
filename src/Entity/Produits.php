<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['produit:read', 'stock:read'])]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['produit:read', 'stock:read'])]


    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['produit:read', 'stock:read'])]


    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $suppr = null;


    /**
     * @var Collection<int, Stock>
     */
    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'produit')]

    private Collection $stocks;

    /**
     * @var Collection<int, MouvementStock>
     */
    #[ORM\OneToMany(targetEntity: MouvementStock::class, mappedBy: 'produits')]
    private Collection $mouvementStocks;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['produit:read'])]

    private ?Famille $famille = null;

    /**
     * @var Collection<int, UnitesP>
     */
    #[ORM\OneToMany(targetEntity: UnitesP::class, mappedBy: 'produits')]
    #[Groups(['produit:read'])]

    private Collection $unite;

    #[ORM\Column]
    #[Groups(['produit:read'])]

    private ?int $seuil = null;

    public function __construct()
    {
        $this->unite = new ArrayCollection();
    }


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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setProduit($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getProduit() === $this) {
                $stock->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MouvementStock>
     */
    public function getMouvementStocks(): Collection
    {
        return $this->mouvementStocks;
    }

    public function addMouvementStock(MouvementStock $mouvementStock): static
    {
        if (!$this->mouvementStocks->contains($mouvementStock)) {
            $this->mouvementStocks->add($mouvementStock);
            $mouvementStock->setProduits($this);
        }

        return $this;
    }

    public function removeMouvementStock(MouvementStock $mouvementStock): static
    {
        if ($this->mouvementStocks->removeElement($mouvementStock)) {
            // set the owning side to null (unless already changed)
            if ($mouvementStock->getProduits() === $this) {
                $mouvementStock->setProduits(null);
            }
        }

        return $this;
    }

    public function getFamille(): ?Famille
    {
        return $this->famille;
    }

    public function setFamille(?Famille $famille): static
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * @return Collection<int, UnitesP>
     */
    public function getUnite(): Collection
    {
        return $this->unite;
    }

    public function addUnite(UnitesP $unite): static
    {
        if (!$this->unite->contains($unite)) {
            $this->unite->add($unite);
            $unite->setProduits($this);
        }

        return $this;
    }

    public function removeUnite(UnitesP $unite): static
    {
        if ($this->unite->removeElement($unite)) {
            // set the owning side to null (unless already changed)
            if ($unite->getProduits() === $this) {
                $unite->setProduits(null);
            }
        }

        return $this;
    }

    public function getSeuil(): ?int
    {
        return $this->seuil;
    }

    public function setSeuil(int $seuil): static
    {
        $this->seuil = $seuil;

        return $this;
    }
}
