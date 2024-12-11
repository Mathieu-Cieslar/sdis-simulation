<?php

namespace App\Entity;

use App\Repository\CapteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CapteurRepository::class)]
class Capteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeCapteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coorX = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coorY = null;

    /**
     * @var Collection<int, InfoCapteur>
     */
    #[ORM\OneToMany(targetEntity: InfoCapteur::class, mappedBy: 'capteur')]
    private Collection $info;

    public function __construct()
    {
        $this->info = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeCapteur(): ?string
    {
        return $this->typeCapteur;
    }

    public function setTypeCapteur(?string $typeCapteur): static
    {
        $this->typeCapteur = $typeCapteur;

        return $this;
    }

    public function getCoorX(): ?string
    {
        return $this->coorX;
    }

    public function setCoorX(?string $coorX): static
    {
        $this->coorX = $coorX;

        return $this;
    }

    public function getCoorY(): ?string
    {
        return $this->coorY;
    }

    public function setCoorY(?string $coorY): static
    {
        $this->coorY = $coorY;

        return $this;
    }

    /**
     * @return Collection<int, InfoCapteur>
     */
    public function getInfo(): Collection
    {
        return $this->info;
    }

    public function addInfo(InfoCapteur $info): static
    {
        if (!$this->info->contains($info)) {
            $this->info->add($info);
            $info->setCapteur($this);
        }

        return $this;
    }

    public function removeInfo(InfoCapteur $info): static
    {
        if ($this->info->removeElement($info)) {
            // set the owning side to null (unless already changed)
            if ($info->getCapteur() === $this) {
                $info->setCapteur(null);
            }
        }

        return $this;
    }
}
