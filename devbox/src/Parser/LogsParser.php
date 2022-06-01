<?php

declare(strict_types=1);

namespace App\Parser;

use App\Entity\Log;

/**
 * LogsParser
 */
class LogsParser implements ParserInterface
{

    /**
     * extractData
     *
     * @param  string $input
     * @return array
     */
    protected function extractData(string $input): array
    {
        preg_match(
            '/(.*)[\s]+\-\s\-[\s]+\[(.*)\]\s*\"(.*)\"\s*(\d\d\d)/',
            $input,
            $matches
        );
        return [
            "serviceName" => $matches[1],
            "date" => $matches[2],
            "request" => $matches[3],
            "statusCode" => $matches[4]
        ];
    }

    /**
     * parse
     *
     * @param  string $input
     * @return Log
     */
    public function parse(string $input): Log
    {
        return (new Log)->fromAssociativeArray($this->extractData($input));
    }
}
