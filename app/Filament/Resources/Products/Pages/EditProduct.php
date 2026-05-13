<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
             ->authorize(fn () => request()->user()?->can('products.view')),
            DeleteAction::make()
            ->authorize(fn () => request()->user()?->can('products.delete')),
        ];
    }
}
