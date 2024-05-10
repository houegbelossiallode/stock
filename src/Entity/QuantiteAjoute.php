<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuantiteAjouteRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: QuantiteAjouteRepository::class)]
class QuantiteAjoute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quantiteAjoutes')]
    private ?Article $article = null;

    #[ORM\Column(length: 25)]
    private ?string $quantite = null;

    #[ORM\Column(type:'datetime_immutable', options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

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

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): static
    {
        $this->quantite = $quantite;

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

    
}
