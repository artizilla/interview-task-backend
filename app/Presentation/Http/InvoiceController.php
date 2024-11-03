<?php

declare(strict_types=1);

namespace App\Presentation\Http;

use App\Infrastructure\Controller;
use App\Modules\Invoices\Api\InvoiceFacadeInterface;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceFacadeInterface $invoiceFacade,
    ) {
    }

    public function approve(string $invoiceId): JsonResponse
    {
        try {
            $this->invoiceFacade->requestInvoiceApproval($invoiceId);
            return response()->json(
                [],
                JsonResponse::HTTP_NO_CONTENT,
            );
        } catch (\LogicException | \RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function reject(string $invoiceId): JsonResponse
    {
        try {
            $this->invoiceFacade->requestInvoiceRejection($invoiceId);
            return response()->json(
                [],
                JsonResponse::HTTP_NO_CONTENT,
            );
        } catch (\LogicException | \RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get($invoiceId): JsonResponse
    {
        try {
            $invoice = $this->invoiceFacade->getInvoice($invoiceId);

            return response()->json(
                $invoice,
                JsonResponse::HTTP_OK,
            );
        } catch (\LogicException | \RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
