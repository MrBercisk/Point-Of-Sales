<?php

namespace App\Filament\Resources\Transactions\Widgets;

use App\Models\WalletTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionStatsWidget extends BaseWidget
{
    // protected static ?string $pollingInterval = '60s';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $today = today();

        $topUpToday    = (float) WalletTransaction::where('type', 'top_up')
                            ->whereDate('created_at', $today)->sum('amount');

        $belanjToday   = (float) WalletTransaction::where('type', 'purchase')
                            ->whereDate('created_at', $today)->sum('amount');

        $totalTrxToday = WalletTransaction::whereDate('created_at', $today)->count();

        return [
            Stat::make('Top Up Hari Ini', 'Rp ' . number_format($topUpToday, 0, ',', '.'))
                ->description(
                    WalletTransaction::where('type', 'top_up')
                        ->whereDate('created_at', $today)->count() . ' transaksi'
                )
                ->color('success')
                ->icon('heroicon-o-arrow-down-circle'),

            Stat::make('Belanja Hari Ini', 'Rp ' . number_format($belanjToday, 0, ',', '.'))
                ->description(
                    WalletTransaction::where('type', 'purchase')
                        ->whereDate('created_at', $today)->count() . ' transaksi'
                )
                ->color('warning')
                ->icon('heroicon-o-shopping-bag'),

            Stat::make('Total Transaksi Hari Ini', $totalTrxToday . ' transaksi')
                ->description('Top up + belanja + refund')
                ->color('info')
                ->icon('heroicon-o-clipboard-document-list'),
        ];
    }
}