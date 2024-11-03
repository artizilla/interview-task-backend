<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api;

use App\Modules\Invoices\Api\Dto\InvoiceViewDto;

interface InvoiceFacadeInterface
{
    public function getInvoice(string $id): InvoiceViewDto;
    public function requestInvoiceApproval(string $id): void;
    public function requestInvoiceRejection(string $id): void;
}
