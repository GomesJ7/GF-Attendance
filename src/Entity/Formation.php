<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $certification = null;

    #[ORM\Column(length: 50)]
    private ?string $specialite = null;

    /**
     * @var Collection<int, Option>
     */
    #[ORM\OneToMany(targetEntity: Option::class, mappedBy: 'formation')]
    private Collection $options;

    /**
     * @var Collection<int, Promotion>
     */
    #[ORM\ManyToMany(targetEntity: Promotion::class, mappedBy: 'formation')]
    private Collection $promotions;

    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->promotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCertification(): ?string
    {
        return $this->certification;
    }

    public function setCertification(string $certification): static
    {
        $this->certification = $certification;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): static
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->setFormation($this);
        }

        return $this;
    }

    public function removeOption(Option $option): static
    {
        if ($this->options->removeElement($option)) {
            // set the owning side to null (unless already changed)
            if ($option->getFormation() === $this) {
                $option->setFormation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Promotion>
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): static
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions->add($promotion);
            $promotion->addFormation($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): static
    {
        if ($this->promotions->removeElement($promotion)) {
            $promotion->removeFormation($this);
        }

        return $this;
    }
}
