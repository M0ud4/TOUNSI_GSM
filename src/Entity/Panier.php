<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private bool $isActive = true;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, LignedeCommande>
     */
    #[ORM\OneToMany(targetEntity: LignedeCommande::class, mappedBy: 'Id_panier')]
    private Collection $lignedeCommandes;

    public function __construct()
    {
        $this->lignedeCommandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection<int, LignedeCommande>
     */
    public function getLignedeCommandes(): Collection
    {
        return $this->lignedeCommandes;
    }

    public function addLignedeCommande(LignedeCommande $lignedeCommande): static
    {
        if (!$this->lignedeCommandes->contains($lignedeCommande)) {
            $this->lignedeCommandes->add($lignedeCommande);
            $lignedeCommande->setIdPanier($this);
        }

        return $this;
    }

    public function removeLignedeCommande(LignedeCommande $lignedeCommande): static
    {
        if ($this->lignedeCommandes->removeElement($lignedeCommande)) {
            // set the owning side to null (unless already changed)
            if ($lignedeCommande->getIdPanier() === $this) {
                $lignedeCommande->setIdPanier(null);
            }
        }

        return $this;
    }
}