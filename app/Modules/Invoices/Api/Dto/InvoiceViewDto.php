<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Api\Dto;

use App\Modules\Invoices\Domain\Entity\Company;
use App\Modules\Invoices\Domain\Entity\Invoice;
use App\Modules\Invoices\Domain\Entity\Product;

readonly class InvoiceViewDto
{
    public string $number;
    public string $date;
    public string $dueDate;
    public array $company;
    public string $status;
    public array $products;
    public float $totalPrice;

    public function __construct(Invoice $invoice)
    {
        $this->number = $invoice->number->toString();
        $this->date = $invoice->date->format('Y-m-d');
        $this->dueDate = $invoice->dueDate->format('Y-m-d');
        $this->company = $this->mapCompanyToArray($invoice->company);
        $this->status = $invoice->status->value;
        $this->products = array_map([$this, 'mapProductToArray'], $invoice->products);
        $this->totalPrice = $invoice->getTotalPrice();
    }

    private function mapCompanyToArray(Company $company): array
    {
        return [
            'name' => $company->name,
            'street' => $company->street,
            'city' => $company->city,
            'zip' => $company->zipCode,
            'phone' => $company->phone,
            'email' => $company->email,
        ];
    }

    private function mapProductToArray(Product $product): array
    {
        return [
            'name' => $product->name,
            'quantity' => $product->quantity,
            'unitPrice' => $product->price,
            'total' => $product->getTotal(),
        ];
    }
}
