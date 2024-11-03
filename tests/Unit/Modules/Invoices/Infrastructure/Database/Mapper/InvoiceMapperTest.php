<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Infrastructure\Database\Mapper;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Entity\Company;
use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Domain\Entity\Product;
use App\Modules\Invoices\Domain\ValueObject\Price;
use App\Modules\Invoices\Infrastructure\Database\Mapper\InvoiceMapper;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class InvoiceMapperTest extends TestCase
{
    private InvoiceMapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new InvoiceMapper();
    }

    public function testMapToDomain(): void
    {
        $data = $this->createMockData();
        $invoice = $this->mapper->mapToDomain($data);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertTrue(Uuid::isValid($invoice->id->toString()));
        $this->assertEquals($data->invoice->id, $invoice->id->toString());
        $this->assertEquals($data->invoice->number, $invoice->number->toString());
        $this->assertEquals(new \DateTime($data->invoice->date), $invoice->date);
        $this->assertEquals(new \DateTime($data->invoice->due_date), $invoice->dueDate);
        $this->assertEquals(StatusEnum::from($data->invoice->status), $invoice->status);
        $this->assertEquals(new \DateTime($data->invoice->created_at), $invoice->createdAt);
        $this->assertEquals(new \DateTime($data->invoice->updated_at), $invoice->updatedAt);

        $this->assertCompany($invoice->company, $data->invoice);
        $this->assertCount(count($data->products), $invoice->products);

        foreach ($invoice->products as $index => $product) {
            $this->assertProduct($product, $data->products[$index]);
        }
    }

    private function assertCompany(Company $company, object $invoiceRecord): void
    {
        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals($invoiceRecord->company_id, $company->id->toString());
        $this->assertEquals($invoiceRecord->company_name, $company->name);
        $this->assertEquals($invoiceRecord->company_street, $company->street);
        $this->assertEquals($invoiceRecord->company_city, $company->city);
        $this->assertEquals($invoiceRecord->company_zip, $company->zipCode);
        $this->assertEquals($invoiceRecord->company_phone, $company->phone);
        $this->assertEquals($invoiceRecord->company_email, $company->email);
        $this->assertEquals(new \DateTime($invoiceRecord->company_created_at), $company->created_at);
        $this->assertEquals(new \DateTime($invoiceRecord->company_updated_at), $company->updated_at);
    }

    private function assertProduct(Product $product, object $productRecord): void
    {
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($productRecord->id, $product->id->toString());
        $this->assertEquals($productRecord->name, $product->name);
        $this->assertEquals($productRecord->quantity, $product->quantity);

        $price = $product->price;
        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals($productRecord->price, $price->amount);
        $this->assertEquals($productRecord->currency, $price->currency);
    }

    private function createMockData(): object
    {
        return (object) [
            'invoice' => (object) [
                'id' => Uuid::uuid4()->toString(),
                'number' => Uuid::uuid4()->toString(),
                'date' => '2023-11-01',
                'due_date' => '2023-12-01',
                'status' => StatusEnum::APPROVED->value,
                'created_at' => '2023-10-01 12:00:00',
                'updated_at' => '2023-11-01 12:00:00',
                'company_id' => Uuid::uuid4()->toString(),
                'company_name' => 'Test Company',
                'company_street' => '123 Main St',
                'company_city' => 'Test City',
                'company_zip' => '12345',
                'company_phone' => '123-456-7890',
                'company_email' => 'info@testcompany.com',
                'company_created_at' => '2022-01-01 09:00:00',
                'company_updated_at' => '2022-01-10 09:00:00',
            ],
            'products' => [
                (object) [
                    'id' => Uuid::uuid4()->toString(),
                    'name' => 'Product A',
                    'price' => 100.00,
                    'currency' => 'USD',
                    'quantity' => 2,
                ],
                (object) [
                    'id' => Uuid::uuid4()->toString(),
                    'name' => 'Product B',
                    'price' => 50.00,
                    'currency' => 'USD',
                    'quantity' => 1,
                ],
            ],
        ];
    }
}
