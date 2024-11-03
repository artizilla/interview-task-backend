<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Application\Listeners;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Invoices\Application\Exceptions\NotFoundException;
use App\Modules\Invoices\Application\Listeners\InvoiceApproveNotification;
use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Domain\Entity\InvoiceWriteModel;
use App\Modules\Invoices\Domain\Repository\InvoiceWriteModelRepositoryInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InvoiceApproveNotificationTest extends TestCase
{
    private InvoiceWriteModelRepositoryInterface $repository;
    private InvoiceApproveNotification $listener;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(InvoiceWriteModelRepositoryInterface::class);
        $this->listener = new InvoiceApproveNotification($this->repository);
    }

    public function testHandleEntityApproved()
    {
        $invoiceId = Uuid::uuid4();
        $invoice = new InvoiceWriteModel($invoiceId, StatusEnum::DRAFT);

        $this->repository->expects($this->once())
            ->method('getById')
            ->with($invoiceId)
            ->willReturn($invoice);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (InvoiceWriteModel $savedInvoice) {
                return $savedInvoice->getStatus() === StatusEnum::APPROVED;
            }));

        $event = new EntityApproved(new ApprovalDto($invoiceId, StatusEnum::APPROVED, Invoice::class));
        $this->listener->handle($event);
    }

    public function testHandleEntityApprovedInvoiceNotFound()
    {
        $this->expectException(NotFoundException::class);

        $invoiceId = Uuid::uuid4();
        $this->repository->expects($this->once())
            ->method('getById')
            ->with($invoiceId)
            ->willReturn(null);

        $event = new EntityApproved(new ApprovalDto($invoiceId, StatusEnum::APPROVED, Invoice::class));
        $this->listener->handle($event);
    }

    public function testHandleEntityApprovedDifferentEntity()
    {
        $this->repository->expects($this->never())->method('getById');
        $event = new EntityApproved(new ApprovalDto(Uuid::uuid4(), StatusEnum::APPROVED, 'DifferentClass'));
        $this->listener->handle($event);
    }
}
