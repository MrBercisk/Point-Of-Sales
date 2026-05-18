<x-filament-panels::page>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@400;500;600;700;800&display=swap');

    .kds-wrap * { box-sizing: border-box; }

    /* ── Stats ── */
    .kds-stats {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        align-items: center;
    }
    .kds-stat {
        flex: 1;
        min-width: 110px;
        border-radius: 14px;
        padding: 14px 16px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .kds-stat::before {
        content: '';
        position: absolute;
        top: -20px; right: -20px;
        width: 70px; height: 70px;
        border-radius: 50%;
        opacity: 0.1;
    }
    .kds-stat-pending    { background: #fffbeb; border: 2px solid #f59e0b; }
    .kds-stat-pending::before { background: #f59e0b; }
    .kds-stat-process    { background: #eff6ff; border: 2px solid #3b82f6; }
    .kds-stat-process::before { background: #3b82f6; }
    .kds-stat-done       { background: #f0fdf4; border: 2px solid #16a34a; }
    .kds-stat-done::before { background: #16a34a; }

    .kds-stat-num {
        font-family: 'Space Mono', monospace;
        font-size: 2.2rem;
        font-weight: 700;
        line-height: 1;
    }
    .kds-stat-pending .kds-stat-num  { color: #d97706; }
    .kds-stat-process .kds-stat-num  { color: #2563eb; }
    .kds-stat-done    .kds-stat-num  { color: #16a34a; }

    .kds-stat-label {
        font-family: 'Space Mono', monospace;
        font-size: 0.62rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-top: 4px;
    }
    .kds-stat-pending .kds-stat-label  { color: #92400e; }
    .kds-stat-process .kds-stat-label  { color: #1d4ed8; }
    .kds-stat-done    .kds-stat-label  { color: #15803d; }

    .kds-pulse-dot {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-left: auto;
        color: #9ca3af;
        font-size: 0.72rem;
        font-family: 'Space Mono', monospace;
        align-self: center;
        white-space: nowrap;
    }
    .kds-pulse-dot span {
        width: 8px; height: 8px;
        background: #16a34a;
        border-radius: 50%;
        animation: kds-blink 2s ease-in-out infinite;
    }

    /* ── Filter tabs ── */
    .kds-filters {
        display: flex;
        gap: 6px;
        margin-bottom: 20px;
        background: #f3f4f6;
        padding: 4px;
        border-radius: 12px;
        width: fit-content;
        border: 1px solid #e5e7eb;
    }
    .kds-filter-btn {
        padding: 7px 18px;
        border-radius: 9px;
        border: none;
        font-family: 'Space Mono', monospace;
        font-size: 0.72rem;
        letter-spacing: 0.04em;
        cursor: pointer;
        transition: all 0.2s ease;
        background: transparent;
        color: #9ca3af;
    }
    .kds-filter-btn.active-pending { background: #fff; color: #d97706; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
    .kds-filter-btn.active-done    { background: #fff; color: #16a34a; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
    .kds-filter-btn.active-all     { background: #fff; color: #2563eb; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
    .kds-filter-btn:hover          { color: #374151; }

    /* ── Grid ── */
    .kds-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 18px;
        align-items: start;
    }

    /* ── Ticket card ── */
    .kds-ticket {
        border-radius: 16px;
        overflow: hidden;
        font-family: 'DM Sans', sans-serif;
        background: #ffffff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kds-ticket:hover { transform: translateY(-2px); }

    .kds-ticket-pending {
        border: 2px solid #f59e0b;
        box-shadow: 0 4px 20px rgba(245,158,11,0.15);
    }
    .kds-ticket-processing {
        border: 2px solid #3b82f6;
        box-shadow: 0 4px 20px rgba(59,130,246,0.18);
        animation: kds-glow-blue 3s ease-in-out infinite;
    }
    .kds-ticket-done {
        border: 2px solid #bbf7d0;
        box-shadow: 0 2px 10px rgba(22,163,74,0.08);
        opacity: 0.85;
    }
    .kds-ticket-urgent {
        animation: kds-urgent 1.8s ease-in-out infinite !important;
    }

    /* ── Ticket header ── */
    .kds-ticket-header {
        padding: 14px 16px 10px;
        border-bottom: 2px dashed;
        position: relative;
    }
    .kds-ticket-pending    .kds-ticket-header { background: #fffbeb; border-color: #fcd34d; }
    .kds-ticket-processing .kds-ticket-header { background: #eff6ff; border-color: #93c5fd; }
    .kds-ticket-done       .kds-ticket-header { background: #f0fdf4; border-color: #bbf7d0; }

    .kds-ticket-invoice {
        font-family: 'Space Mono', monospace;
        font-size: 0.65rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #9ca3af;
    }
    .kds-ticket-customer {
        font-size: 1rem;
        font-weight: 800;
        color: #111827;
        margin-top: 2px;
        line-height: 1.2;
    }
    .kds-ticket-class {
        font-size: 0.72rem;
        color: #6b7280;
        margin-top: 1px;
    }

    .kds-badge {
        position: absolute;
        top: 14px; right: 14px;
        font-family: 'Space Mono', monospace;
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        padding: 4px 10px;
        border-radius: 999px;
        text-transform: uppercase;
    }
    .kds-ticket-pending    .kds-badge { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
    .kds-ticket-processing .kds-badge { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
    .kds-ticket-done       .kds-badge { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }

    .kds-time {
        font-family: 'Space Mono', monospace;
        font-size: 0.65rem;
        margin-top: 6px;
        color: #9ca3af;
    }
    .kds-time-urgent { color: #ef4444 !important; font-weight: 700; }

    /* ── Perforated edge ── */
    .kds-perf {
        display: flex;
        align-items: center;
        overflow: hidden;
        height: 10px;
        background: #fff;
    }
    .kds-perf-circle {
        width: 14px; height: 14px;
        border-radius: 50%;
        flex-shrink: 0;
        background: #f3f4f6;
    }
    .kds-perf-line {
        flex: 1;
        border-top: 2px dashed;
        opacity: 0.4;
    }
    .kds-ticket-pending    .kds-perf-line { border-color: #f59e0b; }
    .kds-ticket-processing .kds-perf-line { border-color: #3b82f6; }
    .kds-ticket-done       .kds-perf-line { border-color: #16a34a; }

    /* ── Items ── */
    .kds-items { padding: 10px 16px 12px; background: #fff; }
    .kds-item {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        padding: 8px 0;
    }
    .kds-item + .kds-item { border-top: 1px dashed #e5e7eb; }

    .kds-qty-box {
        min-width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Space Mono', monospace;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .kds-ticket-pending    .kds-qty-box { background: #fef3c7; color: #d97706; border: 1px solid #fcd34d; }
    .kds-ticket-processing .kds-qty-box { background: #dbeafe; color: #2563eb; border: 1px solid #93c5fd; }
    .kds-ticket-done       .kds-qty-box { background: #dcfce7; color: #16a34a; border: 1px solid #86efac; }

    .kds-item-name { font-size: 0.875rem; font-weight: 700; color: #111827; line-height: 1.3; }
    .kds-item-mod  { font-size: 0.7rem; color: #6b7280; margin-top: 2px; }
    .kds-item-note { font-size: 0.7rem; color: #d97706; margin-top: 2px; font-style: italic; }

    /* ── Actions ── */
    .kds-actions {
        padding: 10px 14px 14px;
        display: flex;
        gap: 8px;
        background: #fff;
    }
    .kds-btn-main {
        flex: 1;
        padding: 10px;
        border: none;
        border-radius: 10px;
        font-family: 'Space Mono', monospace;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        cursor: pointer;
        transition: all 0.15s ease;
        text-transform: uppercase;
    }
    .kds-ticket-pending    .kds-btn-main { background: #f59e0b; color: #fff; }
    .kds-ticket-processing .kds-btn-main { background: #16a34a; color: #fff; }
    .kds-btn-main:hover { filter: brightness(1.08); transform: translateY(-1px); }
    .kds-btn-main:active { transform: translateY(0); }

    .kds-btn-revert {
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #f9fafb;
        color: #6b7280;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.15s;
    }
    .kds-btn-revert:hover { border-color: #d1d5db; color: #374151; }

    .kds-btn-reopen {
        margin: 0 14px 12px;
        width: calc(100% - 28px);
        padding: 8px;
        border: 1px dashed #d1d5db;
        border-radius: 10px;
        background: transparent;
        color: #9ca3af;
        font-family: 'Space Mono', monospace;
        font-size: 0.65rem;
        cursor: pointer;
        letter-spacing: 0.05em;
        transition: all 0.15s;
        display: block;
    }
    .kds-btn-reopen:hover { border-color: #16a34a; color: #16a34a; }

    /* ── Empty state ── */
    .kds-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        font-family: 'Space Mono', monospace;
        color: #d1d5db;
    }
    .kds-empty-icon { font-size: 3rem; margin-bottom: 12px; }
    .kds-empty-text { font-size: 0.75rem; letter-spacing: 0.08em; text-transform: uppercase; }

    /* ── Animations ── */
    @keyframes kds-blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.2; }
    }
    @keyframes kds-glow-blue {
        0%, 100% { box-shadow: 0 4px 20px rgba(59,130,246,0.18); }
        50%       { box-shadow: 0 4px 28px rgba(59,130,246,0.35); }
    }
    @keyframes kds-urgent {
        0%, 100% { box-shadow: 0 4px 20px rgba(245,158,11,0.15); }
        50%       { box-shadow: 0 0 0 5px rgba(239,68,68,0.2), 0 4px 28px rgba(239,68,68,0.2); }
    }
</style>

<div class="kds-wrap">

    {{-- ── Stats ── --}}
    <div class="kds-stats">
        <div class="kds-stat kds-stat-pending">
            <div class="kds-stat-num">{{ $this->stats['pending'] }}</div>
            <div class="kds-stat-label">⏳ Antri</div>
        </div>
        <div class="kds-stat kds-stat-process">
            <div class="kds-stat-num">{{ $this->stats['processing'] }}</div>
            <div class="kds-stat-label">🔥 Diproses</div>
        </div>
        <div class="kds-stat kds-stat-done">
            <div class="kds-stat-num">{{ $this->stats['done'] }}</div>
            <div class="kds-stat-label">✅ Selesai</div>
        </div>
        <div class="kds-pulse-dot">
            <span></span> Live · 10s
        </div>
    </div>

    {{-- ── Filter Tabs ── --}}
    <div class="kds-filters">
        @php
            $filters = ['active' => '🍳 Aktif', 'done' => '✅ Selesai', 'all' => '📋 Semua Hari Ini'];
            $activeClass = ['active' => 'active-pending', 'done' => 'active-done', 'all' => 'active-all'];
        @endphp
        @foreach($filters as $val => $label)
            <button
                wire:click="setFilter('{{ $val }}')"
                class="kds-filter-btn {{ $filterStatus === $val ? $activeClass[$val] : '' }}"
            >{{ $label }}</button>
        @endforeach
    </div>

    {{-- ── Order Cards ── --}}
    <div class="kds-grid">
        @forelse($this->orders as $order)
            @php
                $st       = $order['status'];
                $cls      = 'kds-ticket-' . ($st === 'processing' ? 'processing' : ($st === 'done' ? 'done' : 'pending'));
                $created  = \Carbon\Carbon::parse($order['created_at']);
                $waitMins = (int) $created->diffInMinutes(now());
                $isUrgent = $st === 'pending' && $waitMins >= 5;
                $timeLabel = match($st) {
                    'done'       => 'Selesai ' . \Carbon\Carbon::parse($order['done_at'])->format('H:i'),
                    'processing' => 'Mulai ' . \Carbon\Carbon::parse($order['called_at'])->format('H:i') . ' · ' . $waitMins . ' mnt',
                    default      => $waitMins . ' menit lalu',
                };
            @endphp

            <div class="kds-ticket {{ $cls }} {{ $isUrgent ? 'kds-ticket-urgent' : '' }}">

                {{-- Header --}}
                <div class="kds-ticket-header">
                    <div class="kds-badge">
                        {{ $st === 'pending' ? 'Antri' : ($st === 'processing' ? 'Diproses' : 'Selesai') }}
                    </div>
                    <div class="kds-ticket-invoice">{{ $order['invoice_number'] ?? "ORD-{$order['id']}" }}</div>
                    <div class="kds-ticket-customer">{{ $order['customer_name'] ?? 'Tamu' }}</div>
                    @if($order['customer_class'])
                        <div class="kds-ticket-class">Kelas {{ $order['customer_class'] }}</div>
                    @endif
                    <div class="kds-time {{ $isUrgent ? 'kds-time-urgent' : '' }}">
                        {{ $isUrgent ? '⚠️ ' : '🕐 ' }}{{ $timeLabel }}
                    </div>
                </div>

                {{-- Perforated edge --}}
                <div class="kds-perf">
                    <div class="kds-perf-circle" style="margin-left:-7px;"></div>
                    <div class="kds-perf-line"></div>
                    <div class="kds-perf-circle" style="margin-right:-7px;"></div>
                </div>

                {{-- Items --}}
                <div class="kds-items">
                    @foreach($order['items'] as $item)
                        <div class="kds-item">
                            <div class="kds-qty-box">{{ $item['quantity'] }}</div>
                            <div>
                                <div class="kds-item-name">{{ $item['name'] }}</div>
                                @if(!empty($item['modifiers']))
                                    <div class="kds-item-mod">+ {{ collect($item['modifiers'])->pluck('name')->join(', ') }}</div>
                                @endif
                                @if(!empty($item['note']))
                                    <div class="kds-item-note">📝 {{ $item['note'] }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Perforated edge bawah --}}
                <div class="kds-perf">
                    <div class="kds-perf-circle" style="margin-left:-7px;"></div>
                    <div class="kds-perf-line"></div>
                    <div class="kds-perf-circle" style="margin-right:-7px;"></div>
                </div>

                {{-- Actions --}}
                @if($st !== 'done')
                    <div class="kds-actions">
                        <button
                            wire:click="advanceStatus({{ $order['id'] }})"
                            wire:loading.attr="disabled"
                            wire:target="advanceStatus({{ $order['id'] }})"
                            class="kds-btn-main"
                        >{{ $st === 'pending' ? '🔥 Mulai Proses' : '✅ Tandai Selesai' }}</button>
                        @if($st === 'processing')
                            <button wire:click="revertStatus({{ $order['id'] }})" class="kds-btn-revert" title="Kembalikan ke Antri">↩</button>
                        @endif
                    </div>
                @else
                    <button wire:click="revertStatus({{ $order['id'] }})" class="kds-btn-reopen">↩ buka kembali</button>
                @endif

            </div>
        @empty
            <div class="kds-empty">
                <div class="kds-empty-icon">🍽️</div>
                <div class="kds-empty-text">
                    @if($filterStatus === 'active') Tidak ada order aktif
                    @elseif($filterStatus === 'done') Belum ada order selesai hari ini
                    @else Belum ada order hari ini
                    @endif
                </div>
            </div>
        @endforelse
    </div>

</div>

</x-filament-panels::page>