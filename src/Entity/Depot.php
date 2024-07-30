<?php

namespace App\Entity;

use App\Repository\DepotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DepotRepository::class)]
class Depot
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
    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'depot')]

    private Collection $stocks;

    /**
     * @var Collection<int, MouvementStock>
     */
    #[ORM\OneToMany(targetEntity: MouvementStock::class, mappedBy: 'destinationDepot')]

    private Collection $mouvementStocksDestination;

    /**
     * @var Collection<int, Inventaire>
     */
    #[ORM\OneToMany(targetEntity: Inventaire::class, mappedBy: 'depot')]

    private Collection $inventaires;

    /**
     * @var Collection<int, CommandeFournisseur>
     */
    #[ORM\OneToMany(targetEntity: CommandeFournisseur::class, mappedBy: 'depot')]

    private Collection $commandeFournisseurs;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->mouvementStocksDestination = new ArrayCollection();
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
            $stock->setDepot($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getDepot() === $this) {
                $stock->setDepot(null);
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
            $mouvementStocksDestination->setDestinationDepot($this);
        }

        return $this;
    }

    public function removeMouvementStocksDestination(MouvementStock $mouvementStocksDestination): static
    {
        if ($this->mouvementStocksDestination->removeElement($mouvementStocksDestination)) {
            // set the owning side to null (unless already changed)
            if ($mouvementStocksDestination->getDestinationDepot() === $this) {
                $mouvementStocksDestination->setDestinationDepot(null);
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
            $inventaire->setDepot($this);
        }

        return $this;
    }

    public function removeInventaire(Inventaire $inventaire): static
    {
        if ($this->inventaires->removeElement($inventaire)) {
            // set the owning side to null (unless already changed)
            if ($inventaire->getDepot() === $this) {
                $inventaire->setDepot(null);
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
            $commandeFournisseur->setDepot($this);
        }

        return $this;
    }

    public function removeCommandeFournisseur(CommandeFournisseur $commandeFournisseur): static
    {
        if ($this->commandeFournisseurs->removeElement($commandeFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($commandeFournisseur->getDepot() === $this) {
                $commandeFournisseur->setDepot(null);
            }
        }

        return $this;
    }
}
