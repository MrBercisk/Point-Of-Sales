<?php

namespace App\Filament\Resources\Topup\Pages;

use App\Filament\Resources\Topup\TopUpResource;
use App\Filament\Resources\Topup\Widgets\TopUpStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTopUps extends ListRecords
{
    protected static string $resource = TopUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Top Up')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TopUpStatsWidget::class,
        ];
    }
}