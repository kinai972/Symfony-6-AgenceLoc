<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
#[Vich\Uploadable]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Assert\NotBlank(message: "Le titre du véhicule est obligatoire.")]
    #[Assert\Length(
        max: 200,
        maxMessage: "Le titre du véhicule doit contenir {{ limit }} caractères maximum."
    )]
    private ?string $title = null;

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

    #[Assert\NotBlank(message: 'L\'image du véhicule est obligatoire.', groups: ['create'])]
    #[Vich\UploadableField(mapping: 'vehicles', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\OneToMany(mappedBy: 'vehicle', targetEntity: Renting::class)]
    private Collection $rentings;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->rentings = new ArrayCollection();
        $this->registeredAt = new \DateTime();
    }

    public function __toString()
    {
        return $this->id . ' - ' . $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): self
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTime();
        }

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
