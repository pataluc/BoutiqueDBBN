<?php

namespace App\Entity;

use App\Model\TypeBeneficiaire;
use App\Repository\BeneficiaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BeneficiaireRepository::class)]
class Beneficiaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(enumType: TypeBeneficiaire::class)]
    private ?TypeBeneficiaire $type = null;

    public function __construct(string $_nom, TypeBeneficiaire $_type)
    {
        $this->nom = $_nom;
        $this->type = $_type;
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

    public function getType(): ?TypeBeneficiaire
    {
        return $this->type;
    }

    public function setType(TypeBeneficiaire $type): static
    {
        $this->type = $type;

        return $this;
    }
}
