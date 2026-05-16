<?php
namespace App\Filament\Resources\Modifiers;

use App\Filament\Resources\Modifiers\Pages\CreateModifier;
use App\Filament\Resources\Modifiers\Pages\EditModifier;
use App\Filament\Resources\Modifiers\Pages\ListModifiers;
use App\Filament\Resources\Modifiers\Schemas\ModifierForm;
use App\Filament\Resources\Modifiers\Tables\ModifiersTable;
use App\Models\Modifier;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ModifierResource extends Resource
{
    protected static ?string $model = Modifier::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;
    protected static ?int $navigationSort = 3;
    protected static string|UnitEnum|null $navigationGroup = 'Products';

    //  label yang muncul di sidebar menu navigasi Filament.
    public static function getNavigationLabel(): string
    {
        // return 'Modifier';
        return 'Pilihan Tambahan';
    }

    // label untuk satu record, dipakai di breadcrumb, tombol, notifikasi. 
    public static function getModelLabel(): string
    {
        return 'Pilihan Tambahan';
    }

    // label untuk banyak record, dipakai di judul halaman index, breadcrumb list. 
    public static function getPluralModelLabel(): string
    {
        return 'Pilihan Tambahan';
    }

    public static function form(Schema $schema): Schema
    {
        return ModifierForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ModifiersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListModifiers::route('/'),
            'create' => CreateModifier::route('/create'),
            'edit'   => EditModifier::route('/{record}/edit'),
        ];
    }
}