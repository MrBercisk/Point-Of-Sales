<?php

namespace App\Filament\Pages;

use App\Filament\Traits\HasFilamentPermission;
use App\Models\KitchenOrder;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Livewire\Attributes\Computed;
use UnitEnum;

class KitchenDisplay extends Page
{
    use HasFilamentPermission;
    protected static string $permissionPrefix = 'kitchen-display';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Fire;
    protected static ?string $navigationLabel               = 'Kitchen Display';
    protected static ?string $title                         = 'Kitchen Display';
    protected static ?string $slug                          = 'kitchen-display';
    protected static string|UnitEnum|null $navigationGroup  = 'Sales';
    protected static ?int $navigationSort                   = 3;
    protected string $view                                  = 'filament.pages.kitchen-display';
    protected static ?string $pollingInterval               = '10s';

    public string $filterStatus = 'active';

    #[Computed]
    public function orders(): array
    {
        $query = KitchenOrder::query()->latest();

        if ($this->filterStatus === 'active') {
            $query->whereIn('status', ['pending', 'processing']);
        } elseif ($this->filterStatus === 'done') {
            $query->where('status', 'done')->whereDate('created_at', today());
        } else {
            $query->whereDate('created_at', today());
        }

        return $query->limit(50)->get()->toArray();
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'pending'    => KitchenOrder::where('status', 'pending')->whereDate('created_at', today())->count(),
            'processing' => KitchenOrder::where('status', 'processing')->whereDate('created_at', today())->count(),
            'done'       => KitchenOrder::where('status', 'done')->whereDate('created_at', today())->count(),
        ];
    }

    public function advanceStatus(int $id): void
    {
        $ko = KitchenOrder::find($id);
        if (! $ko) return;
        $ko->advanceStatus();
        unset($this->orders, $this->stats);
    }

    public function revertStatus(int $id): void
    {
        $ko = KitchenOrder::find($id);
        if (! $ko) return;
        $ko->revertStatus();
        unset($this->orders, $this->stats);
    }

    public function setFilter(string $status): void
    {
        $this->filterStatus = $status;
        unset($this->orders, $this->stats);
    }
}