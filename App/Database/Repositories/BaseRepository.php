<?php
namespace App\Database\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class BaseRepository {
    protected EntityManager $entityManager;
    protected EntityRepository $repository;
    protected string $entityClass;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($this->entityClass);
    }

    public function find(int $id) {
        return $this->repository->find($id);
    }

    public function findAll(): array {
        return $this->repository->findAll();
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria): ?object {
        return $this->repository->findOneBy($criteria);
    }

    public function save(object $entity): object {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    public function delete(object $entity): void {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function getEntityManager(): EntityManager {
        return $this->entityManager;
    }
}
