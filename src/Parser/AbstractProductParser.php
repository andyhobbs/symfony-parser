<?php

declare(strict_types=1);

namespace App\Parser;

use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractProductParser
{
    abstract public function supports(string $url): bool;

    public function parse(string $url): array
    {
        $html = @file_get_contents($url);
        if (!$html) {
            return [];
        }

        $crawler = new Crawler($html);

        return $this->extractProducts($crawler);
    }

    abstract protected function extractProducts(Crawler $crawler): array;
}
