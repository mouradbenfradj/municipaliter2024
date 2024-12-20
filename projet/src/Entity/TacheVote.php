<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TacheVoteRepository;

#[ORM\Entity(repositoryClass: TacheVoteRepository::class)]
class TacheVote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Tache::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tache $tache = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: "integer")]
    private int $rating;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTache(): ?Tache
    {
        return $this->tache;
    }

    public function setTache(?Tache $tache): static
    {
        $this->tache = $tache;

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

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }
}
