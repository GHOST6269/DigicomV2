<?php

namespace App\Entity;

use App\Repository\CommandeFournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommandeFournisseurRepository::class)]
class CommandeFournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['produit:read'])]
    private ?int $id = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['produit:read'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups(['produit:read'])]
    private ?string $status = null;

    /**
     * @var Collection<int, DetailscommandeFrs>
     */
    #[ORM\OneToMany(targetEntity: DetailscommandeFrs::class, mappedBy: 'commande')]
    private Collection $detailscommandeFrs;

    /**
     * @var Collection<int, Receptioncommande>
     */
    #[ORM\OneToMany(targetEntity: Receptioncommande::class, mappedBy: 'commande')]
    private Collection $receptioncommandes;

    #[ORM\ManyToOne(inversedBy: 'commandeFournisseurs')]
    private ?Depot $depot = null;

    #[ORM\ManyToOne(inversedBy: 'commandeFournisseurs')]
    private ?Magasin $magasin = null;

    #[ORM\Column]
    #[Groups(['produit:read'])]
    private ?int $montant = null;

    #[ORM\ManyToOne(inversedBy: 'commandeFournisseurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fournisseur $fournisseur = null;

    #[ORM\Column(length: 255)]
    #[Groups(['produit:read'])]
    private ?string $nbl = null;

    public function __construct()
    {
        $this->detailscommandeFrs = new ArrayCollection();
        $this->receptioncommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, DetailscommandeFrs>
     */
    public function getDetailscommandeFrs(): Collection
    {
        return $this->detailscommandeFrs;
    }

    public function addDetailscommandeFr(DetailscommandeFrs $detailscommandeFr): static
    {
        if (!$this->detailscommandeFrs->contains($detailscommandeFr)) {
            $this->detailscommandeFrs->add($detailscommandeFr);
            $detailscommandeFr->setCommande($this);
        }

        return $this;
    }

    public function removeDetailscommandeFr(DetailscommandeFrs $detailscommandeFr): static
    {
        if ($this->detailscommandeFrs->removeElement($detailscommandeFr)) {
            // set the owning side to null (unless already changed)
            if ($detailscommandeFr->getCommande() === $this) {
                $detailscommandeFr->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Receptioncommande>
     */
    public function getReceptioncommandes(): Collection
    {
        return $this->receptioncommandes;
    }

    public function addReceptioncommande(Receptioncommande $receptioncommande): static
    {
        if (!$this->receptioncommandes->contains($receptioncommande)) {
            $this->receptioncommandes->add($receptioncommande);
            $receptioncommande->setCommande($this);
        }

        return $this;
    }

    public function removeReceptioncommande(Receptioncommande $receptioncommande): static
    {
        if ($this->receptioncommandes->removeElement($receptioncommande)) {
            // set the owning side to null (unless already changed)
            if ($receptioncommande->getCommande() === $this) {
                $receptioncommande->setCommande(null);
            }
        }

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

    public function getMagasin(): ?Magasin
    {
        return $this->magasin;
    }

    public function setMagasin(?Magasin $magasin): static
    {
        $this->magasin = $magasin;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

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

    public function getNbl(): ?string
    {
        return $this->nbl;
    }

    public function setNbl(string $nbl): static
    {
        $this->nbl = $nbl;

        return $this;
    }
}
