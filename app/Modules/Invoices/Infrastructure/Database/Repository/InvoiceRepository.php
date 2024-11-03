<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Repository;

use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Domain\Repository\InvoiceRepositoryInterface;
use App\Modules\Invoices\Infrastructure\Database\Mapper\InvoiceMapper;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\UuidInterface;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function __construct(private readonly InvoiceMapper $mapper)
    {
    }

    public function getById(UuidInterface $id): ?Invoice
    {
        $invoiceRecord = DB::table('invoices')
            ->join('companies', 'invoices.company_id', '=', 'companies.id')
            ->select(
                'invoices.*',
                'companies.id as company_id',
                'companies.name as company_name',
                'companies.street as company_street',
                'companies.city as company_city',
                'companies.zip as company_zip',
                'companies.phone as company_phone',
                'companies.email as company_email',
                'companies.created_at as company_created_at',
                'companies.updated_at as company_updated_at'
            )
            ->where('invoices.number', $id->toString())
            ->first();

        if (!$invoiceRecord) {
            return null;
        }

        $productRecords = DB::table('products')
            ->join('invoice_product_lines', 'products.id', '=', 'invoice_product_lines.product_id')
            ->where('invoice_product_lines.invoice_id', $invoiceRecord->id)
            ->select('products.*', 'invoice_product_lines.quantity')
            ->get();

        $data = (object)[
            'invoice' => $invoiceRecord,
            'products' => $productRecords->toArray(),
        ];

        return $this->mapper->mapToDomain($data);
    }
}
