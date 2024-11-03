<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\ValueObject;

readonly class Price
{
    public function __construct(public float $amount, public string $currency)
    {
    }
}
