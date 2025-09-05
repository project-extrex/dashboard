<?php
namespace App\Database\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'settings')]
class Setting {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', unique: true)]
    private string $key;

    #[ORM\Column(type: 'text')]
    private string $value;

    public function getId(): int {
        return $this->id;
    }

    public function getKey(): string {
        return $this->key;
    }

    public function setKey(string $key): self {
        $this->key = $key;
        return $this;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function setValue(string $value): self {
        $this->value = $value;
        return $this;
    }
}
