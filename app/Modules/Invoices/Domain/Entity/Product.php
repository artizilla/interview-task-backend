<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entity;

use App\Modules\Invoices\Domain\ValueObject\Price;
use Ramsey\Uuid\UuidInterface;

readonly class Product
{
    public function __construct(
        public UuidInterface $id,
        public string $name,
        public Price $price,
        public int $quantity,
    ) {
    }

    public function getTotal(): Price
    {
        return new Price($this->price->amount * $this->quantity, $this->price->currency);
    }
}
