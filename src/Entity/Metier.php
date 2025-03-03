<?php

namespace App\Entity;

use App\Repository\MetierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MetierRepository::class)]
class Metier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_employe = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_metier = null;

    public function getId(): ?int
    {
        return $this->id_employe;
    }

    public function getNomMetier(): ?string
    {
        return $this->nom_metier;
    }

    public function setNomMetier(string $nom_metier): static
    {
        $this->nom_metier = $nom_metier;

        return $this;
    }
}
