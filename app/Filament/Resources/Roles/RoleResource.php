<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Tables\RolesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;
use UnitEnum;
// permission filament
use App\Filament\Traits\HasFilamentPermission;


class RoleResource extends Resource
{
    use HasFilamentPermission;
    protected static string $permissionPrefix = 'roles';

    protected static ?string $model = Role::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;
    protected static ?int $navigationSort = 1;
    protected static string|UnitEnum|null $navigationGroup = 'Settings';
    protected static ?string $recordTitleAttribute = 'name';

    // ── Localization ──────────────────────────────────────────────────

    public static function getNavigationGroup(): ?string
    {
        return 'Settings';
    }

    public static function getNavigationLabel(): string
    {
        return 'Role & Permission';
    }

    public static function getModelLabel(): string
    {
        return 'Role';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Role & Permission';
    }

    // ── Schema ────────────────────────────────────────────────────────

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit'   => EditRole::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}