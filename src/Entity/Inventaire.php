<?php

namespace App\Entity;

use App\Repository\InventaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventaireRepository::class)]
class Inventaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'inventaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Magasin $magasin = null;

    #[ORM\ManyToOne(inversedBy: 'inventaires')]
    private ?Depot $depot = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    /**
     * @var Collection<int, DetailInventaire>
     */
    #[ORM\OneToMany(targetEntity: DetailInventaire::class, mappedBy: 'inventaire')]
    private Collection $detailInventaires;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    public function __construct()
    {
        $this->detailInventaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, DetailInventaire>
     */
    public function getDetailInventaires(): Collection
    {
        return $this->detailInventaires;
    }

    public function addDetailInventaire(DetailInventaire $detailInventaire): static
    {
        if (!$this->detailInventaires->contains($detailInventaire)) {
            $this->detailInventaires->add($detailInventaire);
            $detailInventaire->setInventaire($this);
        }

        return $this;
    }

    public function removeDetailInventaire(DetailInventaire $detailInventaire): static
    {
        if ($this->detailInventaires->removeElement($detailInventaire)) {
            // set the owning side to null (unless already changed)
            if ($detailInventaire->getInventaire() === $this) {
                $detailInventaire->setInventaire(null);
            }
        }

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
}
