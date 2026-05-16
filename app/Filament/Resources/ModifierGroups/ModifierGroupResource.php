<?php
namespace App\Filament\Resources\ModifierGroups;

use App\Filament\Resources\ModifierGroups\Pages\CreateModifierGroup;
use App\Filament\Resources\ModifierGroups\Pages\EditModifierGroup;
use App\Filament\Resources\ModifierGroups\Pages\ListModifierGroups;
use App\Filament\Resources\ModifierGroups\Schemas\ModifierGroupForm;
use App\Filament\Resources\ModifierGroups\Tables\ModifierGroupsTable;
use App\Models\ModifierGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ModifierGroupResource extends Resource
{
    protected static ?string $model = ModifierGroup::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;
    protected static ?int $navigationSort = 2;
    protected static string|UnitEnum|null $navigationGroup = 'Products';

    public static function getNavigationLabel(): string
    {
        return 'Grup Pilihan Tambahan';
        // return 'Grup Modifier';
    }

    public static function getModelLabel(): string
    {
        return 'Grup Pilihan Tambahan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Grup Pilihan Tambahan';
    }

    public static function form(Schema $schema): Schema
    {
        return ModifierGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ModifierGroupsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListModifierGroups::route('/'),
            'create' => CreateModifierGroup::route('/create'),
            'edit'   => EditModifierGroup::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}