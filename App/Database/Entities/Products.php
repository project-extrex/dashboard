<?php

namespace App\Database\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
class Products 
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string")]
    private string $name;

    #[ORM\Column(type:"string", nullable:true)]
    private ?string $image;

    #[ORM\Column(type:"string")]
    private string $action;

    #[ORM\Column(type:"float")]
    private float $price;

    #[ORM\Column(type:"json")]
    private array $actionParams;

    #[ORM\Column(type: "string")]
    private string $description;

    public function __construct(string $name, ?string $image, string $action, float $price, string $description, array $actionParams = []) {
        $this->name = $name;
        $this->image = $image;
        $this->action = $action;
        $this->price = $price;
        $this->actionParams = $actionParams;
        $this->description = $description;
    }

    // --- Getters ---
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getImage(): ?string { return $this->image; }
    public function getAction(): string { return $this->action; }
    public function getPrice(): float { return $this->price; }
    public function getActionParams(): array { return $this->actionParams; }
    public function getDescription(): string { return $this->description; }

    // --- Setters ---
    public function setName(string $name): void { $this->name = $name; }
    public function setImage(?string $image): void { $this->image = $image; }
    public function setAction(string $action): void { $this->action = $action; }
    public function setPrice(float $price): void { $this->price = $price; }
    public function setActionParams(array $params): void { $this->actionParams = $params; }
    public function setDescription(string $description): void { $this->description = $description; }
}