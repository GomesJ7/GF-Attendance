<?php

namespace App\Entity;

use App\Repository\InscrireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscrireRepository::class)]
class Inscrire
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'inscrires')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'inscrires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Promotion $promotion = null;

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }
}
