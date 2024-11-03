<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Application\Services;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Application\Exceptions\NotFoundException;
use App\Modules\Invoices\Application\Services\InvoiceRetrievalService;
use App\Modules\Invoices\Domain\Entity\Company;
use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Domain\Repository\InvoiceRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InvoiceRetrievalServiceTest extends TestCase
{
    private InvoiceRepositoryInterface $repository;
    private InvoiceRetrievalService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(InvoiceRepositoryInterface::class);
        $this->service = new InvoiceRetrievalService($this->repository);
    }

    public function testGetInvoiceReturnsInvoiceWhenFound(): void
    {
        $invoiceId = Uuid::uuid4()->toString();
        $invoice = $this->createInvoice();

        $this->repository->expects($this->once())
            ->method('getById')
            ->with($this->callback(function($uuid) use ($invoiceId) {
                return $uuid->toString() === $invoiceId;
            }))
            ->willReturn($invoice);

        $result = $this->service->getInvoice($invoiceId);

        $this->assertSame($invoice, $result);
    }

    public function testGetInvoiceThrowsNotFoundExceptionWhenInvoiceNotFound(): void
    {
        $missingInvoiceId = Uuid::uuid4()->toString();

        $this->repository->expects($this->once())
            ->method('getById')
            ->with($this->callback(function($uuid) use ($missingInvoiceId) {
                return $uuid->toString() === $missingInvoiceId;
            }))
            ->willReturn(null);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Invoice entity with id $missingInvoiceId not found");

        $this->service->getInvoice($missingInvoiceId);
    }

    private function createInvoice(): Invoice
    {
        $company = new Company(
            Uuid::uuid4(),
            'Company Name',
            'Street',
            'City',
            'ZIP',
            'Phone',
            'Email',
            new \DateTime(),
            new \DateTime()
        );
        return new Invoice(
            id: Uuid::uuid4(),
            number: Uuid::uuid4(),
            date: new \DateTime(),
            dueDate: new \DateTime(),
            company: $company,
            status: StatusEnum::DRAFT,
            createdAt: new \DateTime(),
            updatedAt: new \DateTime(),
            products: [],
        );
    }
}
