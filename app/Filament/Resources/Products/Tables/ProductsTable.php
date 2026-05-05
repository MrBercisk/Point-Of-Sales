<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
     public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('app.image'))
                    ->disk('public')
                    ->square()
                    ->size(50),

                TextColumn::make('name')
                    ->label(__('app.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Product $record): string =>
                        $record->category?->name ?? 'No Category'
                    ),

                TextColumn::make('category.name')
                    ->label(__('app.category'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('price')
                    ->label(__('app.price'))
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('stock')
                    ->label(__('app.stock'))
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match(true) {
                        $state <= 0 => 'danger',
                        $state <= 10 => 'warning',
                        default => 'success',
                    })
                    ->icon(fn (int $state): string => match(true) {
                        $state <= 0 => 'heroicon-o-x-circle',
                        $state <= 10 => 'heroicon-o-exclamation-triangle',
                        default => 'heroicon-o-check-circle',
                    }),

                IconColumn::make('is_active')
                    ->label(__('app.active'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('updated_at')
                    ->label(__('app.updated_at'))
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->native(false),

                TernaryFilter::make('is_active')
                    ->label(__('app.active'))
                    ->boolean()
                    ->trueLabel(__('app.active'))
                    ->falseLabel(__('app.inactive'))
                    ->native(false),

                Filter::make('low_stock')
                    ->label(__('app.low_stock'))
                    ->query(fn ($query) => $query->where('stock', '<=', 10)->where('stock', '>', 0))
                    ->toggle(),

                Filter::make('out_of_stock')
                    ->label(__('app.out_of_stock'))
                    ->query(fn ($query) => $query->where('stock', '<=', 0))
                    ->toggle(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('adjustStock')
                        ->label(__('app.adjust_stock'))
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->color('warning')
                        ->form([
                            Radio::make('type')
                                ->options([
                                    'add' => __('app.add_stock'),
                                    'subtract' => __('app.subtract_stock'),
                                    'set' => __('app.set_stock'),
                                ])
                                ->default('add')
                                ->required()
                                ->inline(),
                            TextInput::make('quantity')
                                ->label(__('app.quantity'))
                                ->numeric()
                                ->required()
                                ->minValue(0),
                        ])
                        ->action(function (Product $record, array $data) {
                            $quantity = (int) $data['quantity'];
                            match($data['type']) {
                                'add' => $record->increment('stock', $quantity),
                                'subtract' => $record->decrement('stock', $quantity),
                                'set' => $record->update(['stock' => $quantity]),
                            };
                        }),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('activate')
                        ->label(__('app.activate'))
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    BulkAction::make('deactivate')
                        ->label(__('app.deactivate'))
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('name', 'asc')
            ->striped();
    }
}
