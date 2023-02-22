<?php

namespace App\Entity;

use App\Repository\RentingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RentingRepository::class)]
class Renting
{
    const STARTED_AT = [
        'default' => '2022-01-01 08:00:00',
        'fr' => '1er janvier 2022, 8 h',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date et l'heure de début de la location sont obligatoires.")]
    #[Assert\GreaterThanOrEqual(value: self::STARTED_AT['default'], message: "Veuillez choisir une date de départ postérieure au " . self::STARTED_AT['fr'] . ".")]
    private ?\DateTimeInterface $startsAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date et l'heure de fin de la location sont obligatoires.")]
    #[Assert\GreaterThan(propertyPath: 'startsAt', message: "La date et l'heure de fin de la location doivent être postérieures à la date et à l'heure de départ.")]
    private ?\DateTimeInterface $endsAt = null;

    #[ORM\Column()]
    #[Assert\NotBlank(message: "Le prix total de la location est obligatoire.")]
    private ?float $totalPrice = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registeredAt = null;

    #[ORM\ManyToOne(inversedBy: 'rentings')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'rentings')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Vehicle $vehicle = null;

    #[ORM\Column(length: 255)]
    private ?string $vehicleReference = null;

    public function __construct()
    {
        $this->registeredAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->vehicleReference;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(?\DateTimeInterface $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTimeInterface $endsAt): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getVehicleReference(): ?string
    {
        return $this->vehicleReference;
    }

    public function setVehicleReference(string $vehicleReference): self
    {
        $this->vehicleReference = $vehicleReference;

        return $this;
    }
}
