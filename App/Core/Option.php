<?php

namespace App\Core;

use App\Database\Entities\Options;
use Doctrine\ORM\EntityManagerInterface;

class Option
{
    private static ?EntityManagerInterface $em = null;

    public static function init(EntityManagerInterface $em): void
    {
        self::$em = $em;
    }

    public static function get_option(string $option_name, mixed $default = null): mixed
    {
        if (!self::$em) {
            throw new \RuntimeException("EntityManager not initialized in Option class");
        }

        $repo = self::$em->getRepository(Options::class);
        $option = $repo->findOneBy(['name' => $option_name]);

        return $option ? $option->getValue() : $default;
    }

    public static function set_option(string $option_name, string $value): void
    {
        if (!self::$em) {
            throw new \RuntimeException("EntityManager not initialized in Option class");
        }

        $repo = self::$em->getRepository(Options::class);
        $option = $repo->findOneBy(['name' => $option_name]);

        if (!$option) {
            $option = new Options();
            $option->setName($option_name);
        }

        $option->setValue($value);

        self::$em->persist($option);
        self::$em->flush();
    }
}