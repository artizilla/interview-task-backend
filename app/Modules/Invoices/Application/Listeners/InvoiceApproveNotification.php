<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Application\Listeners;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Invoices\Application\Exceptions\NotFoundException;
use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Domain\Repository\InvoiceWriteModelRepositoryInterface;

readonly class InvoiceApproveNotification
{
    public function __construct(
        private InvoiceWriteModelRepositoryInterface $repository,
    ) {
    }

    public function handle(EntityApproved $event): void
    {
        if (Invoice::class !== $event->approvalDto->entity) {
            return;
        }

        $invoice = $this->repository->getById($event->approvalDto->id);
        if (!$invoice) {
            throw NotFoundException::onMissingInvoice($event->approvalDto->id->toString());
        }

        $invoice->setStatus(StatusEnum::APPROVED);
        $this->repository->save($invoice);
    }
}
