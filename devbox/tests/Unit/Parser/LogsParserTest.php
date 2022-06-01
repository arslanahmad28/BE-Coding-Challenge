<?php

declare(strict_types=1);

namespace Tests\Unit\Parser;

use App\Parser\LogsParser;
use PHPUnit\Framework\TestCase;
use App\Entity\Log;
use DateTime;

class LogsParserTest extends TestCase
{
    public function testParseLogs(): void
    {

        $log = new Log;
        $log->setServiceName("USER-SERVICE");
        $log->setDate(new DateTime("2021-08-17 09:21:53"));
        $log->setRequest("POST /users HTTP/1.1");
        $log->setStatusCode(201);

        $logParser = new LogsParser();
        $logData = $logParser->parse('USER-SERVICE - - [17/Aug/2021:09:21:53 +0000] "POST /users HTTP/1.1" 201');

        $this->assertEquals($logData, $log);
    }
}
