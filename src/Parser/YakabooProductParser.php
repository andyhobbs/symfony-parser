<?php

declare(strict_types=1);

namespace App\Parser;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DomCrawler\Crawler;

#[AutoconfigureTag('app.product_parser')]
class YakabooProductParser extends AbstractProductParser
{
    public function supports(string $url): bool
    {
        return str_contains($url, 'yakaboo.ua');
    }

    protected function extractProducts(Crawler $crawler): array
    {
        $products = [];

        $crawler->filterXPath("//div[contains(@class, 'category-card category-layout')]")->each(
            function (Crawler $nodeCrawler) use (&$products) {
                $title = $nodeCrawler->filterXPath(".//a[contains(@class, 'category-card__name')]")->text('');

                $price = $nodeCrawler->filterXPath(".//div[contains(@class, 'category-card__price')]//div/span[1]")->text('');

                $imageNode = $nodeCrawler->filterXPath(".//img[contains(@class,'product-image__thumb')]");
                $imageUrl = '';
                if ($imageNode->count()) {
                    $imageUrl = $imageNode->attr('data-src') ?: $imageNode->attr('src');
                    if ($imageUrl && strpos($imageUrl, 'http') !== 0) {
                        $imageUrl = 'https:' . $imageUrl;
                    }
                }

                $productUrlNode = $nodeCrawler->filterXPath(".//a[contains(@class,'category-card__name')]");
                $productUrl = $productUrlNode->count() ? $productUrlNode->attr('href') : '';
                if ($productUrl && strpos($productUrl, 'http') !== 0) {
                    $productUrl = 'https://www.yakaboo.ua' . $productUrl;
                }

                $products[] = [
                    'title' => trim($title),
                    'price' => (float)trim($price),
                    'imageUrl' => $imageUrl,
                    'productUrl' => $productUrl,
                ];
            }
        );

        return $products;
    }
}
