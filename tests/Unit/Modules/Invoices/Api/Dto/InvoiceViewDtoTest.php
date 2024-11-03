<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Api\Dto;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Api\Dto\InvoiceViewDto;
use App\Modules\Invoices\Domain\Entity\Company;
use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Domain\Entity\Product;
use App\Modules\Invoices\Domain\ValueObject\Price;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InvoiceViewDtoTest extends TestCase
{
    public function testInvoiceViewDtoMapsInvoiceDataCorrectly()
    {
        $company = new Company(
            id: Uuid::uuid4(),
            name: 'Test Company',
            street: '123 Main St',
            city: 'Sample City',
            zipCode: '12345',
            phone: '555-1234',
            email: 'company@example.com',
            created_at: new \DateTime('2023-01-01'),
            updated_at: new \DateTime('2023-01-01')
        );

        $product = new Product(
            id: Uuid::uuid4(),
            name: 'Test Product',
            price: new Price(50.00, 'USD'),
            quantity: 2
        );

        $invoice = new Invoice(
            id: Uuid::uuid4(),
            number: Uuid::uuid4(),
            date: new \DateTime('2024-01-01'),
            dueDate: new \DateTime('2024-01-10'),
            company: $company,
            status: StatusEnum::APPROVED,
            createdAt: new \DateTime('2023-01-01'),
            updatedAt: new \DateTime('2023-01-01'),
            products: [$product]
        );

        $dto = new InvoiceViewDto($invoice);

        $this->assertSame($invoice->number->toString(), $dto->number);
        $this->assertSame($invoice->date->format('Y-m-d'), $dto->date);
        $this->assertSame($invoice->dueDate->format('Y-m-d'), $dto->dueDate);
        $this->assertSame($invoice->status->value, $dto->status);
        $this->assertGreaterThan(0, $dto->totalPrice);

        $this->assertSame([
            'name' => 'Test Company',
            'street' => '123 Main St',
            'city' => 'Sample City',
            'zip' => '12345',
            'phone' => '555-1234',
            'email' => 'company@example.com',
        ], $dto->company);

        $this->assertCount(1, $dto->products);

        $this->assertEquals([
            'name' => 'Test Product',
            'quantity' => 2,
            'unitPrice' => new Price(50.00, 'USD'),
            'total' => new Price(100.00, 'USD'),
        ], (array)$dto->products[0]);
    }
}
