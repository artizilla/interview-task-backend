<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Mapper;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Entity\Company;
use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Domain\Entity\Product;
use App\Modules\Invoices\Domain\ValueObject\Price;
use Ramsey\Uuid\Uuid;

class InvoiceMapper
{
    public function mapToDomain(object $data): Invoice
    {
        $invoiceRecord = $data->invoice;

        $company = new Company(
            Uuid::fromString($invoiceRecord->company_id),
            $invoiceRecord->company_name,
            $invoiceRecord->company_street,
            $invoiceRecord->company_city,
            $invoiceRecord->company_zip,
            $invoiceRecord->company_phone,
            $invoiceRecord->company_email,
            new \DateTime($invoiceRecord->company_created_at),
            new \DateTime($invoiceRecord->company_updated_at),
        );

        $products = array_map(fn ($productRecord) => new Product(
            Uuid::fromString($productRecord->id),
            $productRecord->name,
            new Price($productRecord->price, $productRecord->currency),
            $productRecord->quantity,
        ), $data->products);

        return new Invoice(
            id: Uuid::fromString($invoiceRecord->id),
            number: Uuid::fromString($invoiceRecord->number),
            date: new \DateTime($invoiceRecord->date),
            dueDate: new \DateTime($invoiceRecord->due_date),
            company: $company,
            status: StatusEnum::from($invoiceRecord->status),
            createdAt: new \DateTime($invoiceRecord->created_at),
            updatedAt: new \DateTime($invoiceRecord->updated_at),
            products: $products,
        );
    }
}
