<?php

namespace App\Livewire;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class OrdersChart extends ChartWidget
{
    protected ?string $heading = 'Orders Last 7 Days';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->subDays(6)->startOfDay(),
                end: now()->endOfDay(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) =>
                Carbon::parse($value->date)->format('d M')
            ),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
