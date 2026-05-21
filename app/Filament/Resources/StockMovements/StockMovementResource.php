<?php

namespace App\Filament\Resources\StockMovements;

use App\Filament\Resources\StockMovements\Pages\ListStockMovements;
use App\Filament\Resources\StockMovements\Pages\CreateStockMovement;
use App\Filament\Resources\StockMovements\Schemas\StockMovementForm;
use App\Filament\Resources\StockMovements\Tables\StockMovementsTable;
use App\Filament\Traits\HasFilamentPermission;
use App\Models\StockMovement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class StockMovementResource extends Resource
{
    use HasFilamentPermission;

    protected static string $permissionPrefix      = 'stock-movements';
    protected static ?string $model                = StockMovement::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;
    protected static ?int $navigationSort          = 3;
    protected static string|UnitEnum|null $navigationGroup = 'Inventory';


    public static function getNavigationGroup(): ?string
    {
        return 'Inventory';
    }

    public static function getNavigationLabel(): string
    {
        return 'Stock Movement';
    }

    public static function getModelLabel(): string
    {
        return 'Stock Movement';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Stock Movements';
    }

    // Schema

    public static function form(Schema $schema): Schema
    {
        return StockMovementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockMovementsTable::configure($table);
    }

    // Pages

    public static function getPages(): array
    {
        return [
            'index'  => ListStockMovements::route('/'),
            'create' => CreateStockMovement::route('/create'),
        ];
    }

    // Badge untuk total movement hari ini

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::whereDate('created_at', today())->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}