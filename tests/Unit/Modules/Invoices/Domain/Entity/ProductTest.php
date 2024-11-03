<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Invoices\Domain\Entity;

use App\Modules\Invoices\Domain\Entity\Product;
use App\Modules\Invoices\Domain\ValueObject\Price;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductTest extends TestCase
{
    /**
     * @dataProvider totalPriceDataProvider
     */
    public function testGetTotal($priceAmount, $currency, $quantity, $expectedTotalAmount, $expectedCurrency)
    {
        $uuid = Uuid::uuid4();
        $price = new Price($priceAmount, $currency);
        $product = new Product($uuid, 'Sample Product', $price, $quantity);

        $totalPrice = $product->getTotal();

        $this->assertEquals($expectedTotalAmount, $totalPrice->amount);
        $this->assertEquals($expectedCurrency, $totalPrice->currency);
    }

    public function totalPriceDataProvider(): array
    {
        return [
            [100, 'USD', 3, 300, 'USD'],
            [50, 'EUR', 5, 250, 'EUR'],
        ];
    }
}
