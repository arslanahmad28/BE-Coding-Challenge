<?php

namespace Tests\Functional\Repository;

use Tests\Integration\DataFixtures\DataFixtureTestCase;
use App\Entity\ImportJob;

class ImportJobRepositoryTest extends DataFixtureTestCase
{
    /** @var  ImportJobRepository $importJobRepository */
    protected $importJobRepository;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->importJobRepository = $this->entityManager->getRepository(ImportJob::class);
    }

    public function testAddImportJob(): void
    {
        $importJob = $this->importJobRepository->addImportJob("Log", "public/logs.txt", 12, 15, "pending");
        $importJobObj = $this->importJobRepository->find($importJob->getId());
        $this->assertEquals($importJob->getId(), $importJobObj->getId());
    }

    public function testUpdateImportJob(): void
    {
        $importJobObj = $this->importJobRepository->find(3);
        $importJob = $this->importJobRepository->updateImportJob($importJobObj, 8, 11, "completed");
        $this->assertEquals("completed", $importJob->getStatus());
    }

    public function testRemove(): void
    {
        $importJob = $this->importJobRepository->find(1);
        $this->importJobRepository->remove($importJob, true);

        $importJob = $this->importJobRepository->find(1);
        $this->assertNull($importJob);
    }

    public function testFindOneByFilePathAndName(): void
    {
        $importJob = $this->importJobRepository->addImportJob("Log", "public/logs.txt", 12, 15, "pending");
        $result = $this->importJobRepository->findOneByFilePathAndName("public/logs.txt", "Log");
        $this->assertEquals($importJob, $result);
    }
}
