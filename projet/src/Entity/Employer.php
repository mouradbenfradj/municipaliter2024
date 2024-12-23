<?php

namespace App\Entity;

use App\Repository\EmployerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployerRepository::class)]
class Employer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'employers')]
    private ?Group $groupe = null;

    #[ORM\OneToMany(targetEntity: Tache::class, mappedBy: 'worker')]
    private Collection $taches;

    #[ORM\OneToMany(mappedBy: 'employer', targetEntity: EmployerVote::class)]
    private Collection $votes;

    #[ORM\Column(length: 255, nullable: true)] 
    private ?string $eval = null;

    public function __construct()
    {
        $this->taches = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getGroupe(): ?Group
    {
        return $this->groupe;
    }

    public function setGroupe(?Group $groupe): static
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection<int, Tache>
     */
    public function getTaches(): Collection
    {
        return $this->taches;
    }

    public function addTach(Tache $tach): static
    {
        if (!$this->taches->contains($tach)) {
            $this->taches->add($tach);
            $tach->setWorker($this);
        }

        return $this;
    }

    public function removeTach(Tache $tach): static
    {
        if ($this->taches->removeElement($tach)) {
            // set the owning side to null (unless already changed)
            if ($tach->getWorker() === $this) {
                $tach->setWorker(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EmployerVote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(EmployerVote $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setEmployer($this);
        }

        return $this;
    }

    public function removeVote(EmployerVote $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getEmployer() === $this) {
                $vote->setEmployer(null);
            }
        }

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
}
