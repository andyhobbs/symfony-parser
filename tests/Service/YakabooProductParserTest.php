<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Parser\YakabooProductParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class YakabooProductParserTest extends TestCase
{
    public function testSupportsYakabooUrls()
    {
        $parser = new YakabooProductParser();

        $this->assertTrue($parser->supports('https://www.yakaboo.ua/ua/category/page.html'));
        $this->assertFalse($parser->supports('https://rozetka.com.ua/category/page.html'));
    }

    public function testExtractProductsParsesExpectedData()
    {
        $html = <<<HTML
<!DOCTYPE html>
<html>
<body>
    <div class="category-card category-layout">
        <a class="category-card__name" href="/book-1.html">Test Book</a>
        <div class="category-card__price"><div><span>123</span></div></div>
        <img class="product-image__thumb" data-src="//images.example.com/image.jpg" />
    </div>
</body>
</html>
HTML;

        $crawler = new Crawler($html);

        $parser = new class extends YakabooProductParser {
            public function publicExtract(Crawler $crawler): array
            {
                return $this->extractProducts($crawler);
            }
        };

        $products = $parser->publicExtract($crawler);

        $this->assertCount(1, $products);

        $this->assertSame('Test Book', $products[0]['title']);
        $this->assertSame(123.0, $products[0]['price']);
        $this->assertSame('https://images.example.com/image.jpg', $products[0]['imageUrl']);
        $this->assertSame('https://www.yakaboo.ua/book-1.html', $products[0]['productUrl']);
    }
}
