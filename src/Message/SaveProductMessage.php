<?php

declare(strict_types=1);

namespace App\Message;

class SaveProductMessage
{
    public function __construct(
        public readonly array $data
    ) {}
}
