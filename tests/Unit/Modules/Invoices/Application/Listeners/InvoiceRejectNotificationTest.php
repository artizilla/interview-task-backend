<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Application\Listeners;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Approval\Api\Events\EntityRejected;
use App\Modules\Invoices\Application\Exceptions\NotFoundException;
use App\Modules\Invoices\Application\Listeners\InvoiceRejectNotification;
use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Domain\Entity\InvoiceWriteModel;
use App\Modules\Invoices\Domain\Repository\InvoiceWriteModelRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class InvoiceRejectNotificationTest extends TestCase
{
    private InvoiceWriteModelRepositoryInterface $repository;
    private InvoiceRejectNotification $listener;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(InvoiceWriteModelRepositoryInterface::class);
        $this->listener = new InvoiceRejectNotification($this->repository);
    }

    public function testHandleDoesNothingForNonInvoiceEntity(): void
    {
        $event = $this->createEntityRejectedEvent('NonInvoiceClass');

        $this->repository->expects($this->never())->method('getById');
        $this->repository->expects($this->never())->method('save');

        $this->listener->handle($event);
    }

    public function testHandleThrowsNotFoundExceptionWhenInvoiceNotFound(): void
    {
        $event = $this->createEntityRejectedEvent(Invoice::class);

        $this->repository->expects($this->once())
            ->method('getById')
            ->with($event->approvalDto->id)
            ->willReturn(null);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Invoice entity with id {$event->approvalDto->id->toString()} not found");

        $this->listener->handle($event);
    }

    public function testHandleSetsStatusToRejectedAndSavesInvoice(): void
    {
        $event = $this->createEntityRejectedEvent(Invoice::class);
        $invoiceWriteModel = $this->createSampleInvoiceWriteModel($event->approvalDto->id);

        $this->repository->expects($this->once())
            ->method('getById')
            ->with($event->approvalDto->id)
            ->willReturn($invoiceWriteModel);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (InvoiceWriteModel $savedModel) {
                return $savedModel->getStatus() === StatusEnum::REJECTED;
            }));

        $this->listener->handle($event);
    }

    private function createEntityRejectedEvent(string $entityClass): EntityRejected
    {
        return new EntityRejected(new ApprovalDto(
            Uuid::uuid4(),
            StatusEnum::REJECTED,
            $entityClass
        ));
    }

    private function createSampleInvoiceWriteModel(UuidInterface $id): InvoiceWriteModel
    {
        return new InvoiceWriteModel(
            id: $id,
            status: StatusEnum::DRAFT
        );
    }
}
