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

// permission filament
use App\Filament\Traits\HasFilamentPermission;

class ModifierResource extends Resource
{
    use HasFilamentPermission;
    protected static string $permissionPrefix = 'modifier';
    protected static ?string $model = Modifier::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::PlusCircle;
    protected static ?int $navigationSort = 3;
    protected static string|UnitEnum|null $navigationGroup = 'Products';

    //  label yang muncul di sidebar menu navigasi Filament.
    public static function getNavigationLabel(): string
    {
        // return 'Modifier';
        return 'Add-ons';
    }

    // label untuk satu record, dipakai di breadcrumb, tombol, notifikasi. 
    public static function getModelLabel(): string
    {
        return 'Add-ons';
    }

    // label untuk banyak record, dipakai di judul halaman index, breadcrumb list. 
    public static function getPluralModelLabel(): string
    {
        return 'Add-ons';
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