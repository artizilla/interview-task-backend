<?php

declare(strict_types=1);

namespace App\Modules\Approval\Application\Listeners;

use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Invoices\Events\InvoiceRejectionRequested;

class RejectionRequestNotification
{
    private ApprovalFacadeInterface $approvalFacade;

    public function __construct(ApprovalFacadeInterface $approvalFacade) {
        $this->approvalFacade = $approvalFacade;
    }

    public function handle(InvoiceRejectionRequested $event): void
    {
        $this->approvalFacade->reject(new ApprovalDto(
            $event->invoiceId,
            $event->status,
            $event->entity,
        ));
    }
}
