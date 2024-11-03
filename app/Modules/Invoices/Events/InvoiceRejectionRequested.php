<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Events;

use App\Domain\Enums\StatusEnum;
use Ramsey\Uuid\UuidInterface;

readonly class InvoiceRejectionRequested
{
    public function __construct(
        public UuidInterface $invoiceId,
        public StatusEnum $status,
        public string $entity,
    ) {
    }
}
