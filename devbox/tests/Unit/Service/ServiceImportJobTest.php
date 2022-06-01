<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use App\Entity\ImportJob;
use App\Entity\Log;
use App\Parser\LogsParser;
use App\Service\ServiceImportJob;
use App\Repository\ImportJobRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use SplFileObject;
use DateTime;

use function PHPUnit\Framework\assertEquals;

class ServiceImportJobTest extends TestCase
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var ImportJobRepository */
    protected $importJobRepository;

    /** @var ManagerRegistry */
    protected $doctrine;

    /** @var SplFileObject */
    protected $file;

    public function setUp(): void
    {
        $this->doctrine = $this->createMock(ManagerRegistry::class);
        $this->importJobRepository = $this->createMock(ImportJobRepository::class);
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->file = new SplFileObject("public/logs.txt", "r");
        $this->doctrine->expects($this->any())
            ->method('getManager')
            ->willReturn($this->entityManager);
    }

    public function testGetStartingRow(): void
    {
        $this->importJobRepository->expects($this->any())
            ->method('findOneByFilePathAndName')
            ->willReturn(null);

        $serviceImportJob = new ServiceImportJob($this->doctrine, $this->importJobRepository);
        $startingRow = $serviceImportJob->getStartingRow($this->file, "Log", 3);
        assertEquals($startingRow, 0);
    }

    public function testflushEntitiesAndSetStatusToComplete(): void
    {
        $importJob = $this->createMock(ImportJob::class);
        $importJob->expects($this->any())
            ->method('getStatus')
            ->willReturn("pending");

        $importJob->expects($this->any())
            ->method('getStartingRow')
            ->willReturn(0);

        $this->importJobRepository->expects($this->any())
            ->method('findOneByFilePathAndName')
            ->willReturn($importJob);

        $importJobObj = new ImportJob;
        $importJobObj->setName("Log");
        $importJobObj->setFilePath("public/logs.txt");
        $importJobObj->setStartingRow(0);
        $importJobObj->setEndingRow(3);
        $importJobObj->setStatus("completed");

        $this->importJobRepository->expects($this->any())
            ->method('updateImportJob')
            ->willReturn($importJobObj);

        $serviceImportJob = new ServiceImportJob($this->doctrine, $this->importJobRepository);
        $importJob1 = $serviceImportJob->flushEntitiesAndSetStatusToComplete($this->file, "Log");
        assertEquals($importJob1->getStatus(), "completed");
    }

    public function testImportBatch(): void
    {
        $importJobObj = new ImportJob;
        $importJobObj->setName("Log");
        $importJobObj->setFilePath("public/logs.txt");
        $importJobObj->setStartingRow(0);
        $importJobObj->setEndingRow(3);
        $importJobObj->setStatus("completed");

        $this->importJobRepository->expects($this->any())
            ->method('addImportJob')
            ->willReturn($importJobObj);

        $this->importJobRepository->expects($this->any())
            ->method('findOneByFilePathAndName')
            ->willReturn($importJobObj);

        $log = new Log;
        $log->setServiceName("USER-SERVICE");
        $log->setDate(new DateTime("2021-08-17 09:21:53"));
        $log->setRequest("POST /users HTTP/1.1");
        $log->setStatusCode(201);

        $logParser = $this->createMock(LogsParser::class);
        $logParser->expects($this->any())
            ->method('parse')
            ->willReturn($log);



        $isBatchStarted = true;
        $serviceImportJob = new ServiceImportJob($this->doctrine, $this->importJobRepository);
        $serviceImportJob->importBatch($this->file, $isBatchStarted, $logParser, 3, "Log");
        assertEquals(false, $isBatchStarted);
    }
}
