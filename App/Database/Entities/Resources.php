<?php
namespace App\Database\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: "resources")]
class Resources 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "integer")]
    private int $coins;

    #[ORM\Column(type: "integer")]
    private int $slots;

    #[ORM\Column(type: "integer")]
    private int $disk;

    #[ORM\Column(type: "integer")]
    private int $memory;

    #[ORM\Column(type: "integer")]
    private int $cpu;

    #[ORM\Column(type: "integer")]
    private int $dbs;

    #[ORM\Column(type: "integer")]
    private int $backups;

    #[ORM\Column(type: "integer")]
    private int $allocations;

    #[ORM\Column(type: Types::JSON)]
    private array $extraThings = [];

    #[ORM\OneToOne(mappedBy: "resources", targetEntity: User::class)]
    private ?User $user = null;

    public function getId(): ?int { return $this->id; }

    public function getCoins(): int { return $this->coins; }
    public function setCoins(int $coins): void { $this->coins = $coins; }

    public function getSlots(): int { return $this->slots; }
    public function setSlots(int $slots): void { $this->slots = $slots; }

    public function getDisk(): int { return $this->disk; }
    public function setDisk(int $disk): void { $this->disk = $disk; }

    public function getMemory(): int { return $this->memory; }
    public function setMemory(int $memory): void { $this->memory = $memory; }

    public function getCpu(): int { return $this->cpu; }
    public function setCpu(int $cpu): void { $this->cpu = $cpu; }

    public function getDbs(): int { return $this->dbs; }
    public function setDbs(int $dbs): void { $this->dbs = $dbs; }

    public function getBackups(): int { return $this->backups; }
    public function setBackups(int $backups): void { $this->backups = $backups; }

    public function getAllocations(): int { return $this->allocations; }
    public function setAllocations(int $allocations): void { $this->allocations = $allocations; }

    public function getExtraThings(): array { return $this->extraThings; }
    public function setExtraThings(array $extraThings): void { $this->extraThings = $extraThings; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(User $user): void
    {
        $this->user = $user;
        if ($user->getResources() !== $this) {
            $user->setResources($this); // sync both sides
        }
    }
}