<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Actions\Action as ActionsAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in'         => 'success',
                        'out'        => 'danger',
                        'adjustment' => 'warning',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in'         => 'Stok Masuk',
                        'out'        => 'Stok Keluar',
                        'adjustment' => 'Penyesuaian',
                        default      => $state,
                    }),

                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->formatStateUsing(fn ($state, $record): string =>
                        match ($record->type) {
                            'in'  => '+' . $state,
                            'out' => '-' . $state,
                            default => '~' . $state,
                        }
                    )
                    ->color(fn ($record): string => match ($record->type) {
                        'in'  => 'success',
                        'out' => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('stock_before')
                    ->label('Stok Sebelum')
                    ->numeric()
                    ->toggleable(),

                TextColumn::make('stock_after')
                    ->label('Stok Sesudah')
                    ->numeric()
                    ->toggleable(),

                TextColumn::make('reason')
                    ->label('Alasan')
                    ->default('-')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->reason),

                TextColumn::make('user.name')
                    ->label('Dicatat Oleh')
                    ->default('System')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'in'         => 'Stok Masuk',
                        'out'        => 'Stok Keluar',
                        'adjustment' => 'Penyesuaian',
                    ]),

                SelectFilter::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('created_at')
                    ->label('Tanggal')
                    ->form([
                        DatePicker::make('from')->label('Dari')->native(false),
                        DatePicker::make('until')->label('Sampai')->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'],  fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->actions([
                ActionsAction::make('lihat_produk')
                    ->label('Produk')
                    ->icon('heroicon-o-cube')
                    ->url(fn ($record) => route('filament.admin.resources.products.view', $record->product_id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([]);
    }
}