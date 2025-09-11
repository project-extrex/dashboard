<?php

namespace App\Database\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "servers")]
class Servers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "integer")]
    private int $allocationId;

    #[ORM\Column(type: "integer")]
    private int $serverId;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $serverUUID;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $lastRenewal;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $nextRenewal;

    // Many servers belong to one user
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "servers")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->lastRenewal = new \DateTimeImmutable();
        // Example: renew every 30 days
        $this->nextRenewal = (new \DateTimeImmutable())->modify("+30 days");
    }

    // --- Getters & Setters ---

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAllocationId(): int
    {
        return $this->allocationId;
    }

    public function setAllocationId(int $allocationId): self
    {
        $this->allocationId = $allocationId;
        return $this;
    }

    public function getServerId(): int
    {
        return $this->serverId;
    }

    public function setServerId(int $serverId): self
    {
        $this->serverId = $serverId;
        return $this;
    }

    public function getServerUUID(): string
    {
        return $this->serverUUID;
    }

    public function setServerUUID(string $serverUUID): self
    {
        $this->serverUUID = $serverUUID;
        return $this;
    }

    public function getLastRenewal(): \DateTimeInterface
    {
        return $this->lastRenewal;
    }

    public function setLastRenewal(\DateTimeInterface $lastRenewal): self
    {
        $this->lastRenewal = $lastRenewal;
        return $this;
    }

    public function getNextRenewal(): \DateTimeInterface
    {
        return $this->nextRenewal;
    }

    public function setNextRenewal(\DateTimeInterface $nextRenewal): self
    {
        $this->nextRenewal = $nextRenewal;
        return $this;
    }

    // --- User relationship ---

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;
        return $this;
    }

    // --- Renewal helpers ---

    public function renew(int $days = 30): self
    {
        $this->lastRenewal = new \DateTimeImmutable();
        $this->nextRenewal = (new \DateTimeImmutable())->modify("+{$days} days");
        return $this;
    }

    public function isExpired(): bool
    {
        $now = new \DateTimeImmutable();
        return $now > $this->nextRenewal;
    }

    public function daysUntilRenewal(): int
    {
        $now = new \DateTimeImmutable();
        return (int)$now->diff($this->nextRenewal)->format('%a');
    }
}