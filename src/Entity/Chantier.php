<?php

namespace App\Entity;

use App\Repository\ChantierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Tache;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChantierRepository::class)]
class Chantier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Employe $chef_de_chantier = null;

    /**
     * @var Collection<int, Tache>
     */
    #[ORM\OneToMany(targetEntity: Tache::class, mappedBy: 'chantier')]
    private Collection $taches;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_fin = null;

    #[ORM\ManyToOne(inversedBy: 'chantiers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_tache_suivante = null;

    #[ORM\Column(length: 255)]
    private ?string $numero_de_la_voie = null;

    #[ORM\Column(length: 255)]
    private ?string $type_de_voie = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle_de_la_voie = null;

    #[ORM\Column]
    private ?int $code_postal = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    public function __construct()
    {
        $this->taches = new ArrayCollection();
        $this->date_tache_suivante = $this->date_de_debut;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChefDeChantier(): ?Employe
    {
        return $this->chef_de_chantier;
    }

    public function setChefDeChantier(?Employe $chef_de_chantier): static
    {
        $this->chef_de_chantier = $chef_de_chantier;

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
            $tach->setChantier($this);
        }

        return $this;
    }

    public function removeTach(Tache $tach): static
    {
        if ($this->taches->removeElement($tach)) {
            // set the owning side to null (unless already changed)
            if ($tach->getChantier() === $this) {
                $tach->setChantier(null);
            }
        }

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
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

    public function getDateTacheSuivante(): ?\DateTimeInterface
    {
        return $this->date_tache_suivante;
    }

    public function setDateTacheSuivante(\DateTimeInterface $date_tache_suivante): static
    {
        $this->date_tache_suivante = $date_tache_suivante;

        return $this;
    }

    public function getNumeroDeLaVoie(): ?string
    {
        return $this->numero_de_la_voie;
    }

    public function setNumeroDeLaVoie(string $numero_de_la_voie): static
    {
        $this->numero_de_la_voie = $numero_de_la_voie;

        return $this;
    }

    public function getTypeDeVoie(): ?string
    {
        return $this->type_de_voie;
    }

    public function setTypeDeVoie(string $type_de_voie): static
    {
        $this->type_de_voie = $type_de_voie;

        return $this;
    }

    public function getLibelleDeLaVoie(): ?string
    {
        return $this->libelle_de_la_voie;
    }

    public function setLibelleDeLaVoie(string $libelle_de_la_voie): static
    {
        $this->libelle_de_la_voie = $libelle_de_la_voie;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(int $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }
}
