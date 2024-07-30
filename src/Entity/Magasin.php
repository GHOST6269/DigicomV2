<?php

namespace App\Entity;

use App\Repository\MagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MagasinRepository::class)]
class Magasin
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

    private ?string $adresse = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['produit:read', 'stock:read'])]

    private ?int $suppr = null;

    /**
     * @var Collection<int, Stock>
     */
    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'magasin')]
    private Collection $stocks;

    /**
     * @var Collection<int, MouvementStock>
     */
    #[ORM\OneToMany(targetEntity: MouvementStock::class, mappedBy: 'emplacementoriginMagasin')]

    private Collection $mouvementStocks;

    /**
     * @var Collection<int, MouvementStock>
     */
    #[ORM\OneToMany(targetEntity: MouvementStock::class, mappedBy: 'destinationMagasin')]
    private Collection $mouvementStocksDestination;

    /**
     * @var Collection<int, Vente>
     */
    #[ORM\OneToMany(targetEntity: Vente::class, mappedBy: 'magasin')]
    private Collection $ventes;

    /**
     * @var Collection<int, Inventaire>
     */
    #[ORM\OneToMany(targetEntity: Inventaire::class, mappedBy: 'magasin')]
    private Collection $inventaires;

    /**
     * @var Collection<int, CommandeFournisseur>
     */
    #[ORM\OneToMany(targetEntity: CommandeFournisseur::class, mappedBy: 'magasin')]
    private Collection $commandeFournisseurs;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->mouvementStocks = new ArrayCollection();
        $this->mouvementStocksDestination = new ArrayCollection();
        $this->ventes = new ArrayCollection();
        $this->inventaires = new ArrayCollection();
        $this->commandeFournisseurs = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

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
            $stock->setMagasin($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getMagasin() === $this) {
                $stock->setMagasin(null);
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
            $mouvementStock->setEmplacementoriginMagasin($this);
        }

        return $this;
    }

    public function removeMouvementStock(MouvementStock $mouvementStock): static
    {
        if ($this->mouvementStocks->removeElement($mouvementStock)) {
            // set the owning side to null (unless already changed)
            if ($mouvementStock->getEmplacementoriginMagasin() === $this) {
                $mouvementStock->setEmplacementoriginMagasin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MouvementStock>
     */
    public function getMouvementStocksDestination(): Collection
    {
        return $this->mouvementStocksDestination;
    }

    public function addMouvementStocksDestination(MouvementStock $mouvementStocksDestination): static
    {
        if (!$this->mouvementStocksDestination->contains($mouvementStocksDestination)) {
            $this->mouvementStocksDestination->add($mouvementStocksDestination);
            $mouvementStocksDestination->setDestinationMagasin($this);
        }

        return $this;
    }

    public function removeMouvementStocksDestination(MouvementStock $mouvementStocksDestination): static
    {
        if ($this->mouvementStocksDestination->removeElement($mouvementStocksDestination)) {
            // set the owning side to null (unless already changed)
            if ($mouvementStocksDestination->getDestinationMagasin() === $this) {
                $mouvementStocksDestination->setDestinationMagasin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vente>
     */
    public function getVentes(): Collection
    {
        return $this->ventes;
    }

    public function addVente(Vente $vente): static
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes->add($vente);
            $vente->setMagasin($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): static
    {
        if ($this->ventes->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getMagasin() === $this) {
                $vente->setMagasin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Inventaire>
     */
    public function getInventaires(): Collection
    {
        return $this->inventaires;
    }

    public function addInventaire(Inventaire $inventaire): static
    {
        if (!$this->inventaires->contains($inventaire)) {
            $this->inventaires->add($inventaire);
            $inventaire->setMagasin($this);
        }

        return $this;
    }

    public function removeInventaire(Inventaire $inventaire): static
    {
        if ($this->inventaires->removeElement($inventaire)) {
            // set the owning side to null (unless already changed)
            if ($inventaire->getMagasin() === $this) {
                $inventaire->setMagasin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeFournisseur>
     */
    public function getCommandeFournisseurs(): Collection
    {
        return $this->commandeFournisseurs;
    }

    public function addCommandeFournisseur(CommandeFournisseur $commandeFournisseur): static
    {
        if (!$this->commandeFournisseurs->contains($commandeFournisseur)) {
            $this->commandeFournisseurs->add($commandeFournisseur);
            $commandeFournisseur->setMagasin($this);
        }

        return $this;
    }

    public function removeCommandeFournisseur(CommandeFournisseur $commandeFournisseur): static
    {
        if ($this->commandeFournisseurs->removeElement($commandeFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($commandeFournisseur->getMagasin() === $this) {
                $commandeFournisseur->setMagasin(null);
            }
        }

        return $this;
    }
}
