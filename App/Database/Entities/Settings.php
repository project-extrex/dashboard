<?php

namespace App\Database\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Database\Repositories\SettingsRepository::class)]
#[ORM\Table(name: "settings")]
class Settings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 191, unique: true)]
    private string $name;

    #[ORM\Column(type: "string", length: 255)]
    private string $value;

    public function getId(): int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): void { $this->name = $name; }

    public function getValue(): string { return $this->value; }
    public function setValue(string $value): void { $this->value = $value; }
}