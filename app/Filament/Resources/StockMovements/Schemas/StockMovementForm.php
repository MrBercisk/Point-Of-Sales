<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Stock Movement')
                ->schema([
                    Select::make('product_id')
                        ->label('Produk')
                        ->relationship('product', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            if (! $state) return;
                            $product = \App\Models\Product::find($state);
                            $set('stock_before', $product?->stock ?? 0);
                        })
                        ->helperText('Pilih produk yang stoknya ingin dicatat'),

                    Select::make('type')
                        ->label('Tipe Movement')
                        ->required()
                        ->native(false)
                        ->live()
                        ->options([
                            'in'         => 'Stok Masuk',
                            'out'        => 'Stok Keluar',
                            'adjustment' => 'Penyesuaian Manual',
                        ])
                        ->helperText('Masuk = tambah stok, Keluar = kurangi stok, Penyesuaian = set langsung'),

                    TextInput::make('quantity')
                        ->label(fn (Get $get) => $get('type') === 'adjustment' ? 'Stok Akhir (set ke)' : 'Jumlah')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Get $get, $state, $set) {
                            $type   = $get('type');
                            $before = (int) $get('stock_before');
                            $qty    = (int) $state;

                            $after = match ($type) {
                                'in'         => $before + $qty,
                                'out'        => $before - $qty,
                                'adjustment' => $qty,
                                default      => $before,
                            };

                            $set('stock_after', $after);
                        })
                        ->helperText(fn (Get $get) => $get('type') === 'adjustment'
                            ? 'Masukkan nilai stok yang benar'
                            : 'Jumlah unit yang masuk atau keluar'
                        ),

                    TextInput::make('stock_before')
                        ->label('Stok Sebelum')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->default(0),

                    TextInput::make('stock_after')
                        ->label('Stok Sesudah (preview)')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->default(0),

                    TextInput::make('reason')
                        ->label('Alasan')
                        ->placeholder('Misal: Pembelian dari supplier, Barang rusak, Koreksi stok...')
                        ->maxLength(255),

                    Textarea::make('notes')
                        ->label('Catatan Tambahan')
                        ->placeholder('Detail tambahan jika diperlukan...')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),

        ]);
    }
}