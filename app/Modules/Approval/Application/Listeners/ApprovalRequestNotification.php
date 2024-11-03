<?php

declare(strict_types=1);

namespace App\Modules\Approval\Application\Listeners;

use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Invoices\Events\InvoiceApprovalRequested;

class ApprovalRequestNotification
{
    private ApprovalFacadeInterface $approvalFacade;

    public function __construct(ApprovalFacadeInterface $approvalFacade)
    {
        $this->approvalFacade = $approvalFacade;
    }

    public function handle(InvoiceApprovalRequested $event): void
    {
        $this->approvalFacade->approve(new ApprovalDto(
            $event->invoiceId,
            $event->status,
            $event->entity,
        ));
    }
}
