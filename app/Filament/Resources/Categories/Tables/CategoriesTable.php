<?php
namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('app.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('description')
                    ->label(__('app.description'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(__('app.slug'))
                    ->searchable()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('products_count')
                    ->label(__('app.products'))
                    ->counts('products')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                IconColumn::make('is_active')
                    ->label(__('app.active'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label(__('app.created_at'))
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('app.active'))
                    ->boolean()
                    ->trueLabel(__('app.active'))
                    ->falseLabel(__('app.inactive'))
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (Category $record) {
                        if ($record->products()->count() > 0) {
                            throw new \Exception('Cannot delete category with products');
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc')
            ->striped();
    }
}