<?php

namespace App\Filament\Resources\StockMovements\Pages;

use App\Filament\Resources\StockMovements\StockMovementResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateStockMovement extends CreateRecord
{
    protected static string $resource = StockMovementResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Inject user_id sebelum simpan

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    // Update stok produk setelah movement disimpan

    protected function afterCreate(): void
    {
        $movement = $this->record;
        $product  = $movement->product;

        $product->update(['stock' => $movement->stock_after]);

        Notification::make()
            ->title('Stock Movement berhasil dicatat')
            ->body("Stok {$product->name}: {$movement->stock_before} → {$movement->stock_after}")
            ->success()
            ->send();
    }
}