<?php

use App\Presentation\Http\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('invoices')->group(function (): void {
    Route::get('/{invoice}', [InvoiceController::class, 'get']);
    Route::post('/{invoice}/approve', [InvoiceController::class, 'approve']);
    Route::post('/{invoice}/reject', [InvoiceController::class, 'reject']);
});
