<?php
namespace App\Filament\Resources\Modifiers\Schemas;

use App\Models\ModifierGroup;
use App\Models\Product;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;

class ModifierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsSection::make('Informasi Modifier')
                ->schema([
                    Select::make('modifier_group_id')
                        ->label('Grup Modifier')
                        ->options(ModifierGroup::where('is_active', true)->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    TextInput::make('name')
                        ->label('Nama Modifier')
                        ->placeholder('contoh: Tambah Telur, Ekstra Pedas, Keju')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('price')
                        ->label('Harga Tambahan')
                        ->helperText('Isi 0 kalau gratis')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0)
                        ->required(),

                    Select::make('product_id')
                        ->label('Terhubung ke Stok Produk')
                        ->helperText('Pilih produk bahan baku yang stoknya akan berkurang saat modifier ini dipilih. Kosongkan kalau tidak perlu kurangi stok.')
                        ->options(
                            // hanya tampilkan produk not_for_selling
                            Product::where('not_for_selling', true)
                                ->where('is_active', true)
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->nullable(),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ]),
        ]);
    }
}