<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
             ->authorize(fn () => request()->user()?->can('categories.view')),
            DeleteAction::make()
            ->authorize(fn () => request()->user()?->can('categories.delete')),
        ];
    }
}
