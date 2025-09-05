<?php
namespace App\Core\Database;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class BaseRepository extends EntityRepository {
    protected EntityManager $entityManager;

    public function __construct(EntityManager $entityManager, string $entityClass) {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata($entityClass));
    }

    public function save($entity): void {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove($entity): void {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function findOneOrCreate(array $criteria, array $data = []): object {
        $entity = $this->findOneBy($criteria);
        
        if (!$entity) {
            $entityClass = $this->getClassName();
            $entity = new $entityClass();
            
            foreach ($data as $field => $value) {
                $setter = 'set' . ucfirst($field);
                if (method_exists($entity, $setter)) {
                    $entity->$setter($value);
                }
            }

            $this->save($entity);
        }
        
        return $entity;
    }
}
