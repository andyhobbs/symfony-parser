<?php

declare(strict_types=1);

namespace App\Service;

class ProductCsvWriter
{
    private string $file = __DIR__ . '/../../var/products.csv';
    private bool $initialized = false;

    public function append(array $data): void
    {
        if (!file_exists(dirname($this->file))) {
            mkdir(dirname($this->file), 0777, true);
        }

        $handle = fopen($this->file, 'a');

        if (!$this->initialized && filesize($this->file) === 0) {
            fputcsv($handle, ['Title', 'Price', 'Image URL', 'Product URL']);
            $this->initialized = true;
        }

        fputcsv($handle, [
            $data['title'],
            $data['price'],
            $data['imageUrl'],
            $data['productUrl'],
        ]);

        fclose($handle);
    }
}
