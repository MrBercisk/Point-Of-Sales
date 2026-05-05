<?php

namespace App\Filament\Resources\Transactions\WalletTransactionResource\Pages;

use App\Filament\Resources\Transactions\WalletTransactionResource;
use App\Filament\Resources\Transactions\Widgets\TransactionStatsWidget as WidgetsTransactionStatsWidget;
use Filament\Resources\Pages\ListRecords;

class ListWalletTransactions extends ListRecords
{
    protected static string $resource = WalletTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WidgetsTransactionStatsWidget::class,
        ];
    }
}