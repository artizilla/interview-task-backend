<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Exceptions;

class NotFoundException extends \RuntimeException
{
    public static function onMissingInvoice(string $id): self
    {
        return new self(sprintf('Invoice entity with id %s not found', $id));
    }
}
