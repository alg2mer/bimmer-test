<?php

namespace App\DataFixtures;

use App\Entity\SoftwareVersion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SoftwareVersionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = json_decode(file_get_contents(__DIR__ . '/../../data/softwareversions.json'), true);

        foreach ($data as $row) {
            
            $entity = new SoftwareVersion();

            $entity->setName($row['name']);
            $entity->setSystemVersion($row['system_version']);
            $entity->setSystemVersionAlt($row['system_version_alt']);
            $entity->setLink($row['link']);
            $entity->setStLink($row['st'] ?? null);
            $entity->setGdLink($row['gd'] ?? null);
            $entity->setLatest($row['latest']);

            // Detect LCI (important!)
            $isLci = str_starts_with($row['name'], 'LCI');
            $entity->setIsLci($isLci);

            // Detect type
            if ($isLci) {
                if (str_contains($row['name'], 'CIC')) $entity->setLciType('CIC');
                elseif (str_contains($row['name'], 'NBT')) $entity->setLciType('NBT');
                elseif (str_contains($row['name'], 'EVO')) $entity->setLciType('EVO');
            }

            $manager->persist($entity);
        }

        $manager->flush();
    }
}
