<?php

namespace App\Database\Repositories;

use App\Database\Entities\Settings;
use Doctrine\ORM\EntityRepository;

class SettingsRepository extends EntityRepository
{
    public function getSetting(string $name): ?string
    {
        $setting = $this->findOneBy(['name' => $name]);
        return $setting?->getValue();
    }

    public function setSetting(string $name, string $value): Settings
    {
        $em = $this->getEntityManager(); // <-- use getEntityManager(), not $_em
        $setting = $this->findOneBy(['name' => $name]);

        if (!$setting) {
            $setting = new Settings();
            $setting->setName($name);
        }

        $setting->setValue($value);

        $em->persist($setting);
        $em->flush();

        return $setting;
    }

    public function hasSetting(string $name): bool
    {
        return (bool) $this->findOneBy(['name' => $name]);
    }
}