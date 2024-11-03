<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Repository;

use App\Modules\Invoices\Domain\Entity\InvoiceWriteModel;
use Ramsey\Uuid\UuidInterface;

interface InvoiceWriteModelRepositoryInterface
{
    public function save(InvoiceWriteModel $invoice): void;

    public function getById(UuidInterface $id): ?InvoiceWriteModel;
}
