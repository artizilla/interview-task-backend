<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Domain\Entity;

use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Domain\Entity\Product;
use App\Modules\Invoices\Domain\Entity\Company;
use App\Modules\Invoices\Domain\ValueObject\Price;
use App\Domain\Enums\StatusEnum;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * @dataProvider invoiceTotalPriceProvider
     */
    public function testGetTotalPrice($products, $expectedTotalPrice)
    {
        $uuid = Uuid::uuid4();
        $company = new Company(
            $uuid,
            'Company Name',
            'Street',
            'City',
            'ZIP',
            'Phone',
            'Email',
            new \DateTime(),
            new \DateTime()
        );

        $invoice = new Invoice(
            $uuid,
            Uuid::uuid4(),
            new \DateTime(),
            new \DateTime(),
            $company,
            StatusEnum::DRAFT,
            new \DateTime(),
            new \DateTime(),
            $products
        );

        $totalPrice = $invoice->getTotalPrice();

        $this->assertEquals($expectedTotalPrice, $totalPrice);
    }

    public function invoiceTotalPriceProvider(): array
    {
        return [
            [
                [
                    new Product(Uuid::uuid4(), 'Product 1', new Price(100, 'USD'), 2), // 100*2 = 200
                    new Product(Uuid::uuid4(), 'Product 2', new Price(50, 'USD'), 3),  // 50*3 = 150
                ],
                328.13 // 350 * (1 - 0.0625) = 328.125, rounded to 328.13
            ],
            [
                [
                    new Product(Uuid::uuid4(), 'Product 1', new Price(200, 'USD'), 1), // 200*1 = 200
                    new Product(Uuid::uuid4(), 'Product 2', new Price(75, 'USD'), 4),  // 75*4 = 300
                ],
                468.75 // 500 * (1 - 0.0625) = 468.75
            ]
        ];
    }
}
