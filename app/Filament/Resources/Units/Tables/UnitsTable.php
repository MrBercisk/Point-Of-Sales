<?php

namespace App\Filament\Resources\UnitResource\Schemas;

use Filament\Actions\BulkActionGroup as ActionsBulkActionGroup;
use Filament\Actions\DeleteBulkAction as ActionsDeleteBulkAction;
use Filament\Actions\EditAction as ActionsEditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.unit_name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('short_name')
                    ->label(__('app.short_name'))
                    ->searchable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('products_count')
                    ->label(__('app.products'))
                    ->counts('products')
                    ->sortable(),

                TextColumn::make('created_at')
                ->label(__('app.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                ActionsEditAction::make(),
            ])
            ->bulkActions([
                ActionsBulkActionGroup::make([
                    ActionsDeleteBulkAction::make(),
                ]),
            ]);
    }
}