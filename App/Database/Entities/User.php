<?php
namespace App\Database\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    public function __construct($settingsRepo)
    {
        $this->resources = new Resources();
        $this->resources->setUser($this);  // sync both sides
        $this->resources->setCoins((int) ($settingsRepo->getSetting("default_coins") ?? 0));
        $this->resources->setMemory((int) ($settingsRepo->getSetting("default_memory") ?? 0));
        $this->resources->setDisk((int) ($settingsRepo->getSetting("default_disk") ?? 0));
        $this->resources->setCpu((int) ($settingsRepo->getSetting("default_cpu") ?? 0));
        $this->resources->setDbs((int) ($settingsRepo->getSetting("default_dbs") ?? 0));
        $this->resources->setAllocations((int) ($settingsRepo->getSetting("default_allocations") ?? 0));
        $this->resources->setBackups((int) ($settingsRepo->getSetting("default_backups") ?? 0));
        $this->resources->setSlots((int) ($settingsRepo->getSetting("default_slot") ?? 0));
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(
        inversedBy: 'user',
        targetEntity: Resources::class,
        cascade: ['persist', 'remove']
    )]
    #[ORM\JoinColumn(name: 'resources_id', referencedColumnName: 'id', nullable: false, unique: true)]
    private Resources $resources;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 69, unique: true)]
    private string $username;

    #[ORM\Column(type: 'string', length: 69)]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 69)]
    private string $lastname;

    #[ORM\Column(type: 'integer')]
    private int $ptrlid;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $admin = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResources(): Resources
    {
        return $this->resources;
    }

    public function setResources(Resources $resources): void
    {
        $this->resources = $resources;
        if ($resources->getUser() !== $this) {
            $resources->setUser($this);  // keep both sides in sync
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getPtrlid(): int
    {
        return $this->ptrlid;
    }

    public function setPtrlid(int $ptrlid): void
    {
        $this->ptrlid = $ptrlid;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }
}
