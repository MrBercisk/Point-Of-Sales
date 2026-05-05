<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 11px;
            color: #000;
            width: 72mm;
        }
        .center  { text-align: center; }
        .right   { text-align: right; }
        .bold    { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 4px 0; }

        .store-name {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }
        .store-info {
            font-size: 10px;
            text-align: center;
            margin-bottom: 2px;
        }

        table.meta { width: 100%; }
        table.meta td { font-size: 10px; padding: 1px 0; vertical-align: top; }
        table.meta .meta-label { color: #444; width: 38%; }
        table.meta .meta-value { text-align: right; font-weight: 500; }

        table.items { width: 100%; border-collapse: collapse; }
        table.items td { vertical-align: top; padding: 2px 0; }
        .item-name  { width: 55%; font-size: 11px; font-weight: 600; }
        .item-detail{ font-size: 9px; color: #555; }
        .item-price { width: 45%; text-align: right; font-size: 11px; }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            padding: 1px 0;
        }
        .summary-total {
            font-weight: bold;
            font-size: 13px;
            margin-top: 2px;
        }
        .summary-sub {
            font-size: 10px;
            color: #333;
        }

        .badge {
            display: inline-block;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #000;
            padding: 0 3px;
            border-radius: 2px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 6px;
            line-height: 1.5;
        }
        .footer-brand {
            font-weight: bold;
            font-size: 11px;
            margin-top: 4px;
        }
    </style>
</head>
<body>

    {{-- ── Header toko ── --}}
    <p class="store-name">{{ $storeName }}</p>
    @if($storeAddress)
    <p class="store-info">{{ $storeAddress }}</p>
    @endif
    @if($storePhone)
    <p class="store-info">Telp: {{ $storePhone }}</p>
    @endif

    <div class="divider"></div>

    {{-- ── Info invoice ── --}}
    <table class="meta">
        <tr>
            <td class="meta-label">Invoice</td>
            <td class="meta-value">{{ $order->invoice_number }}</td>
        </tr>
        <tr>
            <td class="meta-label">Tanggal</td>
            <td class="meta-value">{{ $order->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @if($order->customer_name)
        <tr>
            <td class="meta-label">Siswa</td>
            <td class="meta-value">{{ $order->customer_name }}</td>
        </tr>
        @endif
        <tr>
            <td class="meta-label">Bayar</td>
            <td class="meta-value">
                <span class="badge">{{ $order->payment_method === 'wallet' ? 'Dompet' : 'Tunai' }}</span>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- ── Item produk ── --}}
    <table class="items">
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td class="item-name">{{ $item->product->name }}</td>
                <td class="item-price">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2" class="item-detail">
                    {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    {{-- ── Ringkasan pembayaran ── --}}
    <div class="summary-row summary-total">
        <span>TOTAL</span>
        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
    </div>

    @if($order->payment_method === 'cash' && $order->cash_amount)
    <div class="summary-row summary-sub">
        <span>Bayar</span>
        <span>Rp {{ number_format($order->cash_amount, 0, ',', '.') }}</span>
    </div>
    <div class="summary-row summary-sub">
        <span>Kembalian</span>
        <span>Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
    </div>
    @endif

    <div class="divider"></div>

    {{-- ── Footer dari Settings ── --}}
    <div class="footer">
        @foreach(explode("\n", $storeFooter) as $line)
            <p>{{ trim($line) }}</p>
        @endforeach
        <p class="footer-brand">— {{ $storeName }} —</p>
    </div>

</body>
</html>