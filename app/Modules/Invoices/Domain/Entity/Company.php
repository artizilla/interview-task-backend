<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

readonly class Company
{
    public function __construct(
        public UuidInterface $id,
        public string $name,
        public string $street,
        public string $city,
        public string $zipCode,
        public string $phone,
        public string $email,
        public \DateTime $created_at,
        public \DateTime $updated_at,
    ) {
    }
}
