<?php

namespace App\Entity;

use App\Repository\AssignationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssignationRepository::class)]
class Assignation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Employe::class, inversedBy: 'assignations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employe $employe = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_fin = null;

    #[ORM\ManyToOne(inversedBy: 'assignations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tache $tache = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }
    
    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;
        return $this;
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

    public function getDateDeDebut(): ?\DateTimeInterface
    {
        return $this->date_de_debut;
    }

    public function setDateDeDebut(\DateTimeInterface $date_de_debut): static
    {
        $this->date_de_debut = $date_de_debut;

        return $this;
    }

    public function getDateDeFin(): ?\DateTimeInterface
    {
        return $this->date_de_fin;
    }

    public function setDateDeFin(\DateTimeInterface $date_de_fin): static
    {
        $this->date_de_fin = $date_de_fin;

        return $this;
    }
}
