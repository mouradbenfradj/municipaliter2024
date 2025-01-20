<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $referance = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\ManyToOne(inversedBy: 'taches')]
    private ?Employer $worker = null;

    #[ORM\ManyToOne(inversedBy: 'taches')]
    private ?Group $workerGroup = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $debut = null;

    #[ORM\Column(nullable: true)]
    private ?int $estimation = null;

    #[ORM\Column(nullable: true)]
    private ?float $pourcentage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eval = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;
    #[ORM\Column(type: Types::INTEGER)]
    private int $votesOneStar = 0;
    #[ORM\Column(type: Types::INTEGER)]
    private int $votesTwoStars = 0;
    #[ORM\Column(type: Types::INTEGER)]
    private int $votesThreeStars = 0;
    #[ORM\OneToMany(mappedBy: 'tache', targetEntity: Feedback::class)]
    private $feedbacks;

    public function __construct()
    {
        $this->feedbacks = new ArrayCollection();
    }

    public function getFeedbacks(): Collection
    {
        return $this->feedbacks;
    }

    public function addFeedback(Feedback $feedback): self
    {
        if (!$this->feedbacks->contains($feedback)) {
            $this->feedbacks[] = $feedback;
            $feedback->setTache($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): self
    {
        if ($this->feedbacks->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getTache() === $this) {
                $feedback->setTache(null);
            }
        }

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReferance(): ?string
    {
        return $this->referance;
    }

    public function setReferance(string $referance): static
    {
        $this->referance = $referance;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getWorker(): ?Employer
    {
        return $this->worker;
    }

    public function setWorker(?Employer $worker): static
    {
        $this->worker = $worker;

        return $this;
    }

    public function getWorkerGroup(): ?Group
    {
        return $this->workerGroup;
    }

    public function setWorkerGroup(?Group $workerGroup): static
    {
        $this->workerGroup = $workerGroup;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): static
    {
        $this->debut = $debut;

        return $this;
    }

    public function getEstimation(): ?int
    {
        return $this->estimation;
    }

    public function setEstimation(?int $estimation): static
    {
        $this->estimation = $estimation;

        return $this;
    }

    public function getPourcentage(): ?float
    {
        return $this->pourcentage;
    }

    public function setPourcentage(?float $pourcentage): static
    {
        $this->pourcentage = $pourcentage;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getEval(): ?string
    {
        return $this->eval;
    }

    public function setEval(?string $eval): static
    {
        $this->eval = $eval;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }
    public function getVotesOneStar(): int
    {
        return $this->votesOneStar;
    }
    public function setVotesOneStar(int $votesOneStar): static
    {
        $this->votesOneStar = $votesOneStar;
        return $this;
    }
    public function getVotesTwoStars(): int
    {
        return $this->votesTwoStars;
    }
    public function setVotesTwoStars(int $votesTwoStars): static
    {
        $this->votesTwoStars = $votesTwoStars;
        return $this;
    }
    public function getVotesThreeStars(): int
    {
        return $this->votesThreeStars;
    }
    public function setVotesThreeStars(int $votesThreeStars): static
    {
        $this->votesThreeStars = $votesThreeStars;
        return $this;
    }
}
