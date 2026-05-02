<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceiptController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/receipt/{order}/download', [ReceiptController::class, 'download'])
        ->name('receipt.download');
});
