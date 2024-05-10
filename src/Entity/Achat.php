<?php

namespace App\Entity;

use App\Repository\AchatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchatRepository::class)]
class Achat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'achats')]
    private ?Article $article = null;

    #[ORM\ManyToOne(inversedBy: 'achats')]
    private ?Fournisseur $fournisseur = null;

    #[ORM\Column(length: 255)]
    private ?string $quantite = null;

    #[ORM\Column(length: 255)]
    private ?string $prixunit = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $Date = null;


    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

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

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixunit(): ?string
    {
        return $this->prixunit;
    }

    public function setPrixunit(string $prixunit): static
    {
        $this->prixunit = $prixunit;

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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->Date;
    }

    public function setDate(?\DateTimeImmutable $Date): static
    {
        $this->Date = $Date;

        return $this;
    }
}