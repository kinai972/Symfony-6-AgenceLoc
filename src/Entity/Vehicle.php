<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message: "Le nom du véhicule est obligatoire.")]
    #[Assert\Length(
        max: 200,
        maxMessage: "Le nom du véhicule doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "La marque du véhicule est obligatoire.")]
    #[Assert\Length(
        max: 50,
        maxMessage: "La marque du véhicule doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $make = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le modèle du véhicule est obligatoire.")]
    #[Assert\Length(
        max: 50,
        maxMessage: "Le modèle du véhicule doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $model = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "La description du véhicule est obligatoire.")]
    #[Assert\Length(
        min: 15,
        minMessage: "La description du véhicule doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le salaire de l'employé est obligatoire.")]
    #[Assert\Positive(message: "Le prix journalier du véhicule doit être supérieur à 0.")]
    private ?float $dailyPrice = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registeredAt = null;

    #[ORM\Column(length: 200)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'vehicle', targetEntity: Renting::class)]
    private Collection $rentings;

    public function __construct()
    {
        $this->rentings = new ArrayCollection();
        $this->registeredAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function setMake(string $make): self
    {
        $this->make = $make;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDailyPrice(): ?float
    {
        return $this->dailyPrice;
    }

    public function setDailyPrice(float $dailyPrice): self
    {
        $this->dailyPrice = $dailyPrice;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Renting>
     */
    public function getRentings(): Collection
    {
        return $this->rentings;
    }

    public function addRenting(Renting $renting): self
    {
        if (!$this->rentings->contains($renting)) {
            $this->rentings->add($renting);
            $renting->setVehicle($this);
        }

        return $this;
    }

    public function removeRenting(Renting $renting): self
    {
        if ($this->rentings->removeElement($renting)) {
            // set the owning side to null (unless already changed)
            if ($renting->getVehicle() === $this) {
                $renting->setVehicle(null);
            }
        }

        return $this;
    }
}
