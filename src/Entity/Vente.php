<?php

namespace App\Entity;

use App\Repository\VenteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VenteRepository::class)]
class Vente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('client:read')]
    
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ventes')]
    #[ORM\JoinColumn(nullable: false)]
    

    private ?Magasin $magasin = null;
    #[ORM\Column]
    #[Groups('client:read')]

    private ?int $montanttotal = null;
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('client:read')]
    private ?\DateTimeInterface $date = null;

    /**
     * @var Collection<int, DetailVente>
     */
    #[ORM\OneToMany(targetEntity: DetailVente::class, mappedBy: 'vente')]
    

    private Collection $detailVentes;

    #[ORM\ManyToOne(inversedBy: 'ventes')]
    #[ORM\JoinColumn(nullable: true)]
    

    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne]
    private ?Client $client = null;

    #[ORM\Column(length: 255)]
    

    private ?string $modePaiement = null;

    #[ORM\Column(length: 255, nullable: true)]

    private ?string $refPaiement = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups('client:read')]
    private ?int $paid = null;

    public function __construct()
    {
        $this->detailVentes = new ArrayCollection();
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

    public function getMontanttotal(): ?int
    {
        return $this->montanttotal;
    }

    public function setMontanttotal(int $montanttotal): static
    {
        $this->montanttotal = $montanttotal;

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
     * @return Collection<int, DetailVente>
     */
    public function getDetailVentes(): Collection
    {
        return $this->detailVentes;
    }

    public function addDetailVente(DetailVente $detailVente): static
    {
        if (!$this->detailVentes->contains($detailVente)) {
            $this->detailVentes->add($detailVente);
            $detailVente->setVente($this);
        }

        return $this;
    }

    public function removeDetailVente(DetailVente $detailVente): static
    {
        if ($this->detailVentes->removeElement($detailVente)) {
            // set the owning side to null (unless already changed)
            if ($detailVente->getVente() === $this) {
                $detailVente->setVente(null);
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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(string $modePaiement): static
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    public function getRefPaiement(): ?string
    {
        return $this->refPaiement;
    }

    public function setRefPaiement(?string $refPaiement): static
    {
        $this->refPaiement = $refPaiement;

        return $this;
    }

    public function getPaid(): ?int
    {
        return $this->paid;
    }

    public function setPaid(int $paid): static
    {
        $this->paid = $paid;

        return $this;
    }
}
