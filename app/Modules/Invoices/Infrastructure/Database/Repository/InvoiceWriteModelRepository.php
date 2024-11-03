<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Database\Repository;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Entity\InvoiceWriteModel;
use App\Modules\Invoices\Domain\Repository\InvoiceWriteModelRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class InvoiceWriteModelRepository implements InvoiceWriteModelRepositoryInterface
{
    public function save(InvoiceWriteModel $invoice): void
    {
        $data = [
            'id' => $invoice->getId()->toString(),
            'status' => $invoice->getStatus()->value,
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];

        DB::table('invoices')
            ->where('id', $data['id'])
            ->update($data);
    }

    public function getById(UuidInterface $id): ?InvoiceWriteModel
    {
        $invoiceRecord = DB::table('invoices')
            ->select('invoices.*')
            ->where('invoices.id', $id)
            ->first();

        return new InvoiceWriteModel(
            Uuid::fromString($invoiceRecord->id),
            StatusEnum::from($invoiceRecord->status),
        );
    }
}
