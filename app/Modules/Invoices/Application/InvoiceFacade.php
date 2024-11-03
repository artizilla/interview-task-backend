<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application;

use App\Modules\Invoices\Api\Dto\InvoiceViewDto;
use App\Modules\Invoices\Api\InvoiceFacadeInterface;
use App\Modules\Invoices\Application\Services\InvoiceRetrievalService;
use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Events\InvoiceApprovalRequested;
use App\Modules\Invoices\Events\InvoiceRejectionRequested;
use Illuminate\Contracts\Events\Dispatcher;

class InvoiceFacade implements InvoiceFacadeInterface
{
    public function __construct(
        private readonly InvoiceRetrievalService $invoiceService,
        private readonly Dispatcher $dispatcher,
    ) {
    }

    public function getInvoice(string $id): InvoiceViewDto
    {
        $invoice = $this->invoiceService->getInvoice($id);

        return new InvoiceViewDto($invoice);
    }

    public function requestInvoiceApproval(string $id): void
    {
        $invoice = $this->invoiceService->getInvoice($id);

        $this->dispatcher->dispatch(new InvoiceApprovalRequested(
            $invoice->id,
            $invoice->status,
            Invoice::class,
        ));
    }

    public function requestInvoiceRejection(string $id): void
    {
        $invoice = $this->invoiceService->getInvoice($id);

        $this->dispatcher->dispatch(new InvoiceRejectionRequested(
            $invoice->id,
            $invoice->status,
            Invoice::class,
        ));
    }
}
