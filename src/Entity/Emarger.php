<?php

namespace App\Entity;

use App\Repository\EmargerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmargerRepository::class)]
class Emarger
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'emargers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Session $session = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'emargers')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column]
    private ?bool $presence = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $alternative = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $heureArrivee = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $heureDepart = null;

    public function isPresence(): ?bool
    {
        return $this->presence;
    }

    public function setPresence(bool $presence): static
    {
        $this->presence = $presence;

        return $this;
    }

    public function getAlternative(): ?string
    {
        return $this->alternative;
    }

    public function setAlternative(?string $alternative): static
    {
        $this->alternative = $alternative;

        return $this;
    }

    public function getHeureArrivee(): ?\DateTimeImmutable
    {
        return $this->heureArrivee;
    }

    public function setHeureArrivee(\DateTimeImmutable $heureArrivee): static
    {
        $this->heureArrivee = $heureArrivee;

        return $this;
    }

    public function getHeureDepart(): ?\DateTimeImmutable
    {
        return $this->heureDepart;
    }

    public function setHeureDepart(?\DateTimeImmutable $heureDepart): static
    {
        $this->heureDepart = $heureDepart;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): static
    {
        $this->session = $session;

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
