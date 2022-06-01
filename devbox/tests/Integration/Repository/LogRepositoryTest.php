<?php

namespace Tests\Functional\Repository;

use App\Entity\Log;
use DateTime;
use Tests\Integration\DataFixtures\DataFixtureTestCase;

class LogRepositoryTest extends DataFixtureTestCase
{
    /** @var  LogRepository $logRepository */
    protected $logRepository;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->logRepository = $this->entityManager->getRepository(Log::class);
    }

    public function testAdd(): void
    {
        $log = new Log;
        $log->setServiceName("INVOICE-SERVICE");
        $log->setDate(new DateTime("2021-12-20 12:22:53"));
        $log->setRequest("GET /invoices HTTP/1.1");
        $log->setStatusCode(401);

        $this->logRepository->add($log, true);
        $logObj = $this->logRepository->find($log->getId());
        $this->assertEquals($log->getId(), $logObj->getId());
    }

    public function testRemove(): void
    {
        $logObj = $this->logRepository->find(1);
        $this->logRepository->remove($logObj, true);

        $logObj = $this->logRepository->find(1);
        $this->assertNull($logObj);
    }

    /**
     * @dataProvider countFiltererdByFieldsProvider
     */
    public function testCountFilteredByFieldsLogsRecords(array $filterParam, int $expectedCount): void
    {
        $logsCount = $this->logRepository->countByFilteredFields($filterParam);
        $this->assertEquals($expectedCount, ($logsCount));
    }

    /**
     * @return array
     */
    public function countFiltererdByFieldsProvider(): array
    {
        return [
            [
                array(
                    "serviceNames" => ["INVOICE-SERVICE", "USER-SERVICE"],
                    "startDate" => new DateTime("2021-08-17 09:21:53"),
                    "endDate" => new DateTime("2022-08-17 09:21:53"),
                    "statusCode" => 201
                ),
                3
            ],
            [
                array(
                    "serviceNames" => ["INVOICE-SERVICE"],
                    "startDate" => new DateTime("2021-08-17 09:21:53"),
                    "endDate" => new DateTime("2022-08-17 09:21:53"),
                    "statusCode" => 201
                ),
                1
            ],
            [
                array(
                    "serviceNames" => ["USER-SERVICE"],
                    "startDate" => new DateTime("2021-08-17 09:21:53"),
                    "endDate" => new DateTime("2022-08-17 09:21:53"),
                    "statusCode" => 201
                ),
                2
            ],
            [
                array(
                    "serviceNames" => ["USER-SERVICE"],
                    "statusCode" => 201
                ),
                2
            ],
            [
                array(
                    "statusCode" => 400
                ),
                2
            ],
            [
                array(
                    "startDate" => new DateTime("2021-08-17 09:21:53"),
                    "endDate" => new DateTime("2021-09-13 09:21:53"),
                ),
                3
            ],
            [
                array(
                    "serviceNames" => ["INVOICE-SERVICE"],
                    "statusCode" => 400
                ),
                0
            ],
        ];
    }
}
