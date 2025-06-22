<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\Product;
use App\Message\SaveProductMessage;
use App\Service\ProductCsvWriter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SaveProductMessageHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductCsvWriter $csvWriter
    ) {}

    public function __invoke(SaveProductMessage $message): void
    {
        $data = $message->data;
        $product = new Product();
        $product->setTitle($data['title']);
        $product->setPrice($data['price']);
        $product->setImageUrl($data['imageUrl']);
        $product->setProductUrl($data['productUrl']);

        $this->em->persist($product);
        $this->em->flush();

        $this->csvWriter->append($data);
    }
}
