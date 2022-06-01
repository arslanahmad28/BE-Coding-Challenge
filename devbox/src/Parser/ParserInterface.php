<?php

declare(strict_types=1);

namespace App\Parser;

interface ParserInterface
{
    /**
     * parse
     *
     * @param  string $input
     * @return void
     */
    public function parse(string $input);
}
