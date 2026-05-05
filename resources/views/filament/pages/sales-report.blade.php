<x-filament-panels::page>

    {{-- Filter --}}
    <div class="mb-6">
        <x-filament::section>
            <x-slot name="heading">Filter Laporan</x-slot>
            <form wire:submit.prevent="$refresh">
                {{ $this->form }}
                <div class="flex gap-2 mt-4">
                    <x-filament::button type="submit">Apply Filter</x-filament::button>
                    <x-filament::button wire:click="export" color="success">Export Excel</x-filament::button>
                </div>
            </form>
        </x-filament::section>
    </div>

    {{-- Summary Cards --}}
    @php $summary = $this->getSummary(); @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        {{-- Total Pesanan --}}
        <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;border-top:3px solid #6b7280;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#9ca3af;margin:0 0 8px;">Total Pesanan</p>
                    <p style="font-size:32px;font-weight:700;color:#111827;margin:0;line-height:1.1;">{{ number_format($summary['total_orders']) }}</p>
                    <p style="font-size:12px;color:#9ca3af;margin:6px 0 0;">Periode ini</p>
                </div>
                <div style="width:40px;height:40px;border-radius:10px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;font-size:18px;">📦</div>
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:20px;border-top:3px solid #16a34a;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#16a34a;margin:0 0 8px;">Total Pendapatan</p>
                    <p style="font-size:24px;font-weight:700;color:#14532d;margin:0;line-height:1.2;">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
                    <p style="font-size:12px;color:#4ade80;margin:6px 0 0;">Setelah diskon</p>
                </div>
                <div style="width:40px;height:40px;border-radius:10px;background:#dcfce7;display:flex;align-items:center;justify-content:center;font-size:18px;">💰</div>
            </div>
        </div>

        {{-- Rata-rata Pesanan --}}
        <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:12px;padding:20px;border-top:3px solid #0284c7;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <div>
                    <p style="font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#0284c7;margin:0 0 8px;">Rata-rata Pesanan</p>
                    <p style="font-size:24px;font-weight:700;color:#0c4a6e;margin:0;line-height:1.2;">Rp {{ number_format($summary['average_order'], 0, ',', '.') }}</p>
                    <p style="font-size:12px;color:#38bdf8;margin:6px 0 0;">Per transaksi</p>
                </div>
                <div style="width:40px;height:40px;border-radius:10px;background:#e0f2fe;display:flex;align-items:center;justify-content:center;font-size:18px;">📊</div>
            </div>
        </div>

    </div>

    {{-- Table --}}
    {{ $this->table }}

</x-filament-panels::page>