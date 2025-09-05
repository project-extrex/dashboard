<?php
namespace App\Core;

use Doctrine\ORM\EntityManager;
use App\Database\Repositories\UserRepository;
use App\Database\Repositories\SettingsRepository;

class ServiceContainer {
    private static ?ServiceContainer $instance = null;
    private EntityManager $entityManager;
    private array $repositories = [];

    private function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            global $entityManager;
            if (!isset($entityManager)) {
                throw new \RuntimeException('EntityManager not initialized');
            }
            self::$instance = new self($entityManager);
        }
        return self::$instance;
    }

    public function getEntityManager(): EntityManager {
        return $this->entityManager;
    }

    public function getUserRepository(): UserRepository {
        return $this->getRepository(UserRepository::class);
    }

    public function getSettingsRepository(): SettingsRepository {
        return $this->getRepository(SettingsRepository::class);
    }

    private function getRepository(string $class) {
        if (!isset($this->repositories[$class])) {
            $this->repositories[$class] = new $class($this->entityManager);
        }
        return $this->repositories[$class];
    }
}
