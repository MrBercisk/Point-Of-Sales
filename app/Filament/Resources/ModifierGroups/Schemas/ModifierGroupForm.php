<?php
namespace App\Filament\Resources\ModifierGroups\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;

class ModifierGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsSection::make('Informasi Grup Modifier')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Grup')
                        ->placeholder('contoh: Tambahan, Level Pedas, Ukuran')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('max_select')
                        ->label('Maksimal Pilihan')
                        ->helperText('Berapa item yang bisa dipilih dari grup ini')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required(),

                    Toggle::make('is_required')
                        ->label('Wajib Dipilih')
                        ->helperText('Kalau aktif, pelanggan harus memilih minimal 1 dari grup ini')
                        ->default(false),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ]),
        ]);
    }
}