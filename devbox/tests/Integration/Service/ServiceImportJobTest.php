<?php

declare(strict_types=1);

namespace Tests\Integration\Service;

use App\Parser\LogsParser;
use App\Service\ServiceImportJob;
use Tests\Integration\DataFixtures\DataFixtureTestCase;

class ServiceImportJobTest extends DataFixtureTestCase
{
    public function testExecuteImport(): void
    {
        $serviceImportJob = $this->dtcontainer->get(ServiceImportJob::class);
        $serviceImportJob->executeImport(5, "public/logs.txt", new LogsParser(), "Log");
        $this->assertTrue(true);
    }
}
