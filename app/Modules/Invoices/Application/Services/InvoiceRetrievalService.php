<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Services;

use App\Modules\Invoices\Application\Exceptions\NotFoundException;
use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Domain\Repository\InvoiceRepositoryInterface;
use Ramsey\Uuid\Uuid;

class InvoiceRetrievalService
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $repository,
    ) {
    }

    public function getInvoice(string $id): Invoice
    {
        $invoice = $this->repository->getById(Uuid::fromString($id));
        if (null === $invoice) {
            throw NotFoundException::onMissingInvoice($id);
        }

        return $invoice;
    }
}
