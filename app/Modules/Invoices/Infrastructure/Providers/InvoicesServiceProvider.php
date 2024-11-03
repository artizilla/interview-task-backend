<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Providers;

use App\Modules\Invoices\Api\InvoiceFacadeInterface;
use App\Modules\Invoices\Application\InvoiceFacade;
use App\Modules\Invoices\Domain\Repository\InvoiceRepositoryInterface;
use App\Modules\Invoices\Domain\Repository\InvoiceWriteModelRepositoryInterface;
use App\Modules\Invoices\Infrastructure\Database\Repository\InvoiceRepository;
use App\Modules\Invoices\Infrastructure\Database\Repository\InvoiceWriteModelRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class InvoicesServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->scoped(InvoiceWriteModelRepositoryInterface::class, InvoiceWriteModelRepository::class);
        $this->app->scoped(InvoiceFacadeInterface::class, InvoiceFacade::class);
    }

    /** @return array<class-string> */
    public function provides(): array
    {
        return [
            InvoiceRepositoryInterface::class,
            InvoiceWriteModelRepositoryInterface::class,
            InvoiceFacadeInterface::class,
        ];
    }
}
