<?php

namespace Tests\Integration\Controller;

use Tests\Integration\DataFixtures\DataFixtureTestCase;
use function PHPUnit\Framework\assertEquals;

class LogsControllerTest extends DataFixtureTestCase
{
    /**
     * @dataProvider countFiltererdByFieldsProvider
     */
    public function testCountEndPoint(array $filterParam, int $expectedCount): void
    {
        self::$client->request('GET', '/count', $filterParam);
        $response = self::$client->getResponse();

        $this->assertResponseIsSuccessful();
        $responseData = json_decode($response->getContent(), true);
        assertEquals(array("counter" => $expectedCount), $responseData);
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
                    "startDate" => ("2021-08-17 09:21:53"),
                    "endDate" => ("2022-08-17 09:21:53"),
                    "statusCode" => 201
                ),
                3
            ],
            [
                array(
                    "serviceNames" => ["INVOICE-SERVICE"],
                    "startDate" => ("2021-08-17 09:21:53"),
                    "endDate" => ("2022-08-17 09:21:53"),
                    "statusCode" => 201
                ),
                1
            ],
            [
                array(
                    "serviceNames" => ["USER-SERVICE"],
                    "startDate" => ("2021-08-17 09:21:53"),
                    "endDate" => ("2022-08-17 09:21:53"),
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
                    "startDate" => ("2021-08-17 09:21:53"),
                    "endDate" => ("2021-09-13 09:21:53"),
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
