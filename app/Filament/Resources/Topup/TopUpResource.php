<?php

namespace App\Filament\Resources\Topup;

use App\Filament\Resources\Topup\Pages\CreateTopUp;
use App\Filament\Resources\Topup\Pages\ListTopUps;
use App\Filament\Resources\Topup\Schemas\TopUpForm;
use App\Filament\Resources\Topup\Schemas\TopUpTable;
use App\Models\WalletTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TopUpResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;
    protected static string|UnitEnum|null   $navigationGroup = 'Siswa';
    protected static ?string $navigationLabel   = 'Top Up Saldo';
    protected static ?int    $navigationSort    = 2;
    protected static ?string $modelLabel        = 'Top Up';
    protected static ?string $pluralModelLabel  = 'Top Up Saldo';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('type', 'top_up')->latest();
    }

    public static function form(Schema $schema): Schema
    {
        return TopUpForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TopUpTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTopUps::route('/'),
            'create' => CreateTopUp::route('/create'),
        ];
    }
}