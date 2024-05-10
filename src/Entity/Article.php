<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Broadcast]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 100,unique: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prix = null;

    #[ORM\Column]
    
    private ?int $quantite = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\OneToMany(targetEntity: Vente::class, mappedBy: 'article',orphanRemoval: true, cascade: ['persist'])]
    private Collection $ventes;

    #[ORM\OneToMany(targetEntity: Achat::class, mappedBy: 'article',orphanRemoval: true, cascade: ['persist'])]
    private Collection $achats;

    #[ORM\OneToMany(targetEntity: QuantiteAjoute::class, mappedBy: 'article',orphanRemoval: true, cascade: ['persist'])]
    private Collection $quantiteAjoutes;

    #[ORM\Column(type:'datetime_immutable', options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(targetEntity: Inventaire::class, mappedBy: 'article',orphanRemoval: true, cascade: ['persist'])]
    private Collection $inventaires;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->ventes = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->quantiteAjoutes = new ArrayCollection();
        $this->inventaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
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

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function __toString()
    {
    return $this->getNom();
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
            $vente->setArticle($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): static
    {
        if ($this->ventes->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getArticle() === $this) {
                $vente->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Achat>
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): static
    {
        if (!$this->achats->contains($achat)) {
            $this->achats->add($achat);
            $achat->setArticle($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): static
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getArticle() === $this) {
                $achat->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuantiteAjoute>
     */
    public function getQuantiteAjoutes(): Collection
    {
        return $this->quantiteAjoutes;
    }

    public function addQuantiteAjoute(QuantiteAjoute $quantiteAjoute): static
    {
        if (!$this->quantiteAjoutes->contains($quantiteAjoute)) {
            $this->quantiteAjoutes->add($quantiteAjoute);
            $quantiteAjoute->setArticle($this);
        }

        return $this;
    }

    public function removeQuantiteAjoute(QuantiteAjoute $quantiteAjoute): static
    {
        if ($this->quantiteAjoutes->removeElement($quantiteAjoute)) {
            // set the owning side to null (unless already changed)
            if ($quantiteAjoute->getArticle() === $this) {
                $quantiteAjoute->setArticle(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

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
            $inventaire->setArticle($this);
        }

        return $this;
    }

    public function removeInventaire(Inventaire $inventaire): static
    {
        if ($this->inventaires->removeElement($inventaire)) {
            // set the owning side to null (unless already changed)
            if ($inventaire->getArticle() === $this) {
                $inventaire->setArticle(null);
            }
        }

        return $this;
    }

    
}