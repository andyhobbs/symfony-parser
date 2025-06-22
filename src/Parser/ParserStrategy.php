<?php

namespace App\Parser;

class ParserStrategy
{
    /** @param AbstractProductParser[] $parsers */
    public function __construct(private iterable $parsers) {}

    public function getParser(string $url): ?AbstractProductParser
    {
        foreach ($this->parsers as $parser) {
            if ($parser->supports($url)) {
                return $parser;
            }
        }

        return null;
    }
}
