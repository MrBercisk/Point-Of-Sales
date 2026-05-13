<?php
namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $grouped = Permission::all()->groupBy(
            fn($p) => explode('.', $p->name)[0]
        );

        $permissionSections = $grouped->map(function ($perms, $group) {
            return ComponentsSection::make(ucfirst($group))
                ->schema([
                    CheckboxList::make("permissions_{$group}")
                        ->label('')
                        ->options($perms->pluck('name', 'name')->toArray())
                        ->columns(2)
                        ->bulkToggleable()
                        ->gridDirection('row'),
                ])
                ->collapsible()
                ->collapsed();
        })->values()->toArray();

        return $schema->components([
            ComponentsSection::make('Informasi Role')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Role')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->placeholder('Contoh: kasir, admin, supervisor'),
                ])
                ->columns(1),

            ComponentsSection::make('Permissions')
                ->description('Pilih aksi yang diizinkan untuk role ini.')
                ->schema($permissionSections)
                ->collapsible(),
        ]);
    }
}