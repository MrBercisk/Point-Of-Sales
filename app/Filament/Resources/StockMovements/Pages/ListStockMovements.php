<?php

namespace App\Filament\Resources\StockMovements\Pages;

use App\Filament\Resources\StockMovements\StockMovementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Filament\Schemas\Components\Tabs\Tab as TabsTab;
use Illuminate\Database\Eloquent\Builder;

class ListStockMovements extends ListRecords
{
    protected static string $resource = StockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Movement'),
        ];
    }

    // ── Tab filter per tipe ───────────────────────────────────────────

    public function getTabs(): array
    {
        return [
            'all' => TabsTab::make('Semua')
                ->badge(fn () => \App\Models\StockMovement::count()),

            'in' => TabsTab::make('Stok Masuk')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'in'))
                ->badge(fn () => \App\Models\StockMovement::where('type', 'in')->count())
                ->badgeColor('success'),

            'out' => TabsTab::make('Stok Keluar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'out'))
                ->badge(fn () => \App\Models\StockMovement::where('type', 'out')->count())
                ->badgeColor('danger'),

            'adjustment' => TabsTab::make('Penyesuaian')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'adjustment'))
                ->badge(fn () => \App\Models\StockMovement::where('type', 'adjustment')->count())
                ->badgeColor('warning'),

            'today' => TabsTab::make('Hari Ini')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('created_at', today()))
                ->badge(fn () => \App\Models\StockMovement::whereDate('created_at', today())->count())
                ->badgeColor('info'),
        ];
    }
}