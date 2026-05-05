<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\WalletTransactionResource\Pages;
use App\Filament\Resources\Transactions\WalletTransactionResource\Schemas\WalletTransactionTable;
use App\Models\WalletTransaction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static string|\BackedEnum|null $navigationIcon  = Heroicon::ClipboardDocumentList;
    protected static string|\UnitEnum|null   $navigationGroup = 'Siswa';
    protected static ?string $navigationLabel    = 'Riwayat Transaksi';
    protected static ?int    $navigationSort     = 3;
    protected static ?string $modelLabel         = 'Transaksi';
    protected static ?string $pluralModelLabel   = 'Riwayat Transaksi';

    /* Tampilkan semua tipe transaksi, terbaru duluan */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['student', 'user'])->latest();
    }

    /* Tidak ada form create/edit — transaksi dibuat otomatis oleh sistem */
    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return WalletTransactionTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWalletTransactions::route('/'),
        ];
    }
}