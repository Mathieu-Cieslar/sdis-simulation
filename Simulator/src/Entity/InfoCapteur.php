<?php

namespace App\Entity;

use App\Repository\InfoCapteurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfoCapteurRepository::class)]
class InfoCapteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateInfo = null;

    #[ORM\Column(nullable: true)]
    private ?int $valeur = null;

    #[ORM\ManyToOne(inversedBy: 'info')]
    private ?Capteur $capteur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInfo(): ?\DateTimeInterface
    {
        return $this->dateInfo;
    }

    public function setDateInfo(?\DateTimeInterface $dateInfo): static
    {
        $this->dateInfo = $dateInfo;

        return $this;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(?int $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getCapteur(): ?Capteur
    {
        return $this->capteur;
    }

    public function setCapteur(?Capteur $capteur): static
    {
        $this->capteur = $capteur;

        return $this;
    }
}
