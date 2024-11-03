<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entity;

use App\Domain\Enums\StatusEnum;
use Ramsey\Uuid\UuidInterface;

class InvoiceWriteModel
{
    public function __construct(
        private readonly UuidInterface $id,
        private StatusEnum $status,
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    public function setStatus(StatusEnum $status): void
    {
        $this->status = $status;
    }
}
