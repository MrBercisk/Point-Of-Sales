<?php

namespace App\Filament\Resources\Topup\Widgets;

use App\Models\WalletTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TopUpStatsWidget extends BaseWidget
{
    // Auto refresh tiap 30 detik
    // protected static ?string $pollingInterval = '30s';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $todayTopUps = WalletTransaction::where('type', 'top_up')
            ->whereDate('created_at', today());

        $totalToday   = (float) (clone $todayTopUps)->sum('amount');
        $countToday   = (clone $todayTopUps)->count();
        $totalAllTime = (float) WalletTransaction::where('type', 'top_up')->sum('amount');

        return [
            Stat::make('Top Up Hari Ini', 'Rp ' . number_format($totalToday, 0, ',', '.'))
                ->description("{$countToday} transaksi hari ini")
                ->color('success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Jumlah Transaksi', "{$countToday} transaksi")
                ->description('Hari ini')
                ->color('info')
                ->icon('heroicon-o-arrow-trending-up'),

            Stat::make('Total Keseluruhan', 'Rp ' . number_format($totalAllTime, 0, ',', '.'))
                ->description('Sejak pertama digunakan')
                ->color('warning')
                ->icon('heroicon-o-chart-bar'),
        ];
    }
}