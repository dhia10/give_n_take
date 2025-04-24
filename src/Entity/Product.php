<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $silverprice = null;

    #[ORM\Column(nullable: true)]
    private ?int $goldprice = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdat = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isavailable = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $listedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSilverprice(): ?int
    {
        return $this->silverprice;
    }

    public function setSilverprice(?int $silverprice): static
    {
        $this->silverprice = $silverprice;

        return $this;
    }

    public function getGoldprice(): ?int
    {
        return $this->goldprice;
    }

    public function setGoldprice(?int $goldprice): static
    {
        $this->goldprice = $goldprice;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedat(): ?\DateTimeInterface
    {
        return $this->createdat;
    }

    public function setCreatedat(\DateTimeInterface $createdat): static
    {
        $this->createdat = $createdat;

        return $this;
    }

    public function isavailable(): ?bool
    {
        return $this->isavailable;
    }

    public function setIsavailable(?bool $isavailable): static
    {
        $this->isavailable = $isavailable;

        return $this;
    }

    public function getListedBy(): ?User
    {
        return $this->listedBy;
    }

    public function setListedBy(?User $listedBy): static
    {
        $this->listedBy = $listedBy;

        return $this;
    }
}
