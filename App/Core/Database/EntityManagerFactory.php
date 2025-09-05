<?php
namespace App\Core\Database;

use Doctrine\ORM\EntityManager;

class EntityManagerFactory {
    private static ?EntityManager $entityManager = null;

    public static function getEntityManager(): EntityManager {
        if (self::$entityManager === null) {
            // Get config from environment or configuration file
            $config = require dirname(__DIR__, 2) . '/config.php';
            
            // Create EntityManager
            $paths = [dirname(__DIR__, 2) . '/Database/Entities'];
            $isDevMode = true;

            $dbConfig = \Doctrine\ORM\ORMSetup::createAttributeMetadataConfiguration(
                $paths,
                $isDevMode
            );

            self::$entityManager = new EntityManager($config['database'], $dbConfig);
        }

        return self::$entityManager;
    }
}
