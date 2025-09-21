<?php

namespace App\Database\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "eggs")]
class Eggs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "string", length: 100)]
    private string $category;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $dockerImage;

    #[ORM\Column(type: "text")]
    private string $startup;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $icon = null;

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\Column(type: "datetime")]
    private \DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getCategory(): string { return $this->category; }
    public function getDescription(): ?string { return $this->description; }
    public function getDockerImage(): string { return $this->dockerImage; }
    public function getStartup(): string { return $this->startup; }
    public function getIcon(): ?string { return $this->icon; }
    public function getCreatedAt(): \DateTime { return $this->createdAt; }
    public function getUpdatedAt(): \DateTime { return $this->updatedAt; }

    public function setName(string $name): self {
        $this->name = $name;
        return $this->touch();
    }

    public function setCategory(string $category): self {
        $this->category = $category;
        return $this->touch();
    }

    public function setDescription(?string $description): self {
        $this->description = $description;
        return $this->touch();
    }

    public function setDockerImage(string $dockerImage): self {
        $this->dockerImage = $dockerImage;
        return $this->touch();
    }

    public function setStartup(string $startup): self {
        $this->startup = $startup;
        return $this->touch();
    }

    public function setIcon(?string $icon): self {
        $this->icon = $icon;
        return $this->touch();
    }

    private function touch(): self {
        $this->updatedAt = new \DateTime();
        return $this;
    }
}