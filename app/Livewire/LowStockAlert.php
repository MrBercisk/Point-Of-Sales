<?php

namespace App\Livewire;

use App\Models\Product as ModelsProduct;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LowStockAlert extends TableWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Low Stock Alert';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ModelsProduct::query()
                    ->where('is_active', true)
                    ->where('stock', '<=', 10)
                    ->orderBy('stock', 'asc')
            )
            ->columns([
               TextColumn::make('image')
                ->label('Image')
                ->html()
                ->getStateUsing(fn ($record) => $record->image 
                    ? '<img src="' . asset('storage/' . $record->image) . '" style="width:40px;height:40px;object-fit:cover;border-radius:6px;">' 
                    : '-'
                ),

                TextColumn::make('name')
                    ->searchable()
                    ->description(fn (ModelsProduct $record) => $record->category?->name),

                TextColumn::make('stock')
                    ->badge()
                    ->color(fn (int $state): string => $state <= 0 ? 'danger' : 'warning'),

                TextColumn::make('price')
                    ->money('IDR'),
            ])
            ->actions([
                Action::make('restock')
                    ->label('Restock')
                    ->icon('heroicon-o-plus')
                    ->url(fn (ModelsProduct $record) => route('filament.admin.resources.products.edit', $record)),
            ])
            ->paginated(false)
            ->emptyStateHeading('All products are well-stocked!')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
