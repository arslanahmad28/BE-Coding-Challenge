<?php

namespace App\DataFixtures;

use App\Entity\ImportJob;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * ImportJobFixture
 */
class ImportJobFixture extends Fixture
{
    /**
     * load
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $importJob = new ImportJob;
        $importJob->setName("Log");
        $importJob->setFilePath("public/logs.txt");
        $importJob->setStartingRow(0);
        $importJob->setEndingRow(3);
        $importJob->setStatus("complete");
        $manager->persist($importJob);

        $importJob = new ImportJob;
        $importJob->setName("Log");
        $importJob->setFilePath("public/logs.txt");
        $importJob->setStartingRow(4);
        $importJob->setEndingRow(7);
        $importJob->setStatus("complete");
        $manager->persist($importJob);

        $importJob = new ImportJob;
        $importJob->setName("Log");
        $importJob->setFilePath("public/logs.txt");
        $importJob->setStartingRow(8);
        $importJob->setEndingRow(11);
        $importJob->setStatus("pending");
        $manager->persist($importJob);

        $manager->flush();
    }
}
