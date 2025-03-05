<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?bool $est_chef_de_chantier = null;

    #[ORM\ManyToOne(inversedBy: 'employes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Metier $metier = null;

    /**
     * @var Collection<int, Tache>
     */
    #[ORM\ManyToMany(targetEntity: Tache::class, mappedBy: 'employes')]
    private Collection $taches;

    #[ORM\Column]
    private ?bool $disponible = null;

    /**
     * @var Collection<int, Assignation>
     */
    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: Assignation::class)]
    private Collection $assignations;

    public function __construct()
    {
        $this->taches = new ArrayCollection();
        $this->assignations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isEstChefDeChantier(): ?bool
    {
        return $this->est_chef_de_chantier;
    }

    public function setEstChefDeChantier(bool $est_chef_de_chantier): static
    {
        $this->est_chef_de_chantier = $est_chef_de_chantier;

        return $this;
    }

    public function getMetier(): ?Metier
    {
        return $this->metier;
    }

    public function setMetier(?Metier $metier): static
    {
        $this->metier = $metier;

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
            $tach->addEmploye($this);
        }

        return $this;
    }

    public function removeTach(Tache $tach): static
    {
        if ($this->taches->removeElement($tach)) {
            $tach->removeEmploye($this);
        }

        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): static
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * @return Collection<int, Assignation>
     */
    public function getAssignations(): Collection
    {
        return $this->assignations;
    }

    public function addAssignation(Assignation $assignation): self
    {
        if (!$this->assignations->contains($assignation)) {
            $this->assignations[] = $assignation;
            $assignation->setEmploye($this);
        }
        return $this;
    }

    public function removeAssignation(Assignation $assignation): self
    {
        if ($this->assignations->removeElement($assignation)) {
            if ($assignation->getEmploye() === $this) {
                $assignation->setEmploye(null);
            }
        }
        return $this;
    }
}
