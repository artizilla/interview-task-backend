<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entity;

use App\Domain\Enums\StatusEnum;
use Ramsey\Uuid\UuidInterface;

readonly class Invoice
{
    public function __construct(
        public UuidInterface $id,
        public UuidInterface $number,
        public \DateTime $date,
        public \DateTime $dueDate,
        public Company $company,
        public StatusEnum $status,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public array $products,
    ) {
    }

    public function getTotalPrice(): float
    {
        // assume all product prices have the same currency
        $totalPrice = array_reduce($this->products, function ($carry, $product) {
            return $carry + $product->getTotal()->amount;
        }, 0.0);

        $totalPriceAfterTax = $totalPrice * (1 - 0.0625);

        return round($totalPriceAfterTax, 2);
    }
}
