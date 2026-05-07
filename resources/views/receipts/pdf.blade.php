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
            width: {{ $paperSize === '58mm' ? '52mm' : '72mm' }};
        }
        .center  { text-align: center; }
        .right   { text-align: right; }
        .bold    { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 4px 0; }

        .store-name  { font-size: 14px; font-weight: bold; text-align: center; }
        .store-info  { font-size: 10px; text-align: center; margin-bottom: 2px; }

        table.meta { width: 100%; }
        table.meta td { font-size: 10px; padding: 1px 0; vertical-align: top; }
        table.meta .meta-label { color: #444; width: 38%; }
        table.meta .meta-value { text-align: right; font-weight: 500; }

        /* ── Layout: standard ── */
        table.items { width: 100%; border-collapse: collapse; }
        table.items td { vertical-align: top; padding: 2px 0; }
        .item-name   { width: 55%; font-size: 11px; font-weight: 600; }
        .item-detail { font-size: 9px; color: #555; }
        .item-price  { width: 45%; text-align: right; font-size: 11px; }

        /* ── Layout: compact ── */
        .item-compact { display: flex; justify-content: space-between; font-size: 11px; padding: 1px 0; }
        .item-compact-name  { flex: 1; }
        .item-compact-price { text-align: right; white-space: nowrap; }

        /* ── Layout: detailed ── */
        table.items-detailed { width: 100%; border-collapse: collapse; font-size: 10px; }
        table.items-detailed th { text-align: left; border-bottom: 1px solid #ccc; padding: 1px 2px; font-size: 9px; }
        table.items-detailed th.right { text-align: right; }
        table.items-detailed td { padding: 2px 2px; vertical-align: top; }
        .td-qty   { text-align: center; width: 20px; }
        .td-total { text-align: right; white-space: nowrap; }

        .summary-row   { display: flex; justify-content: space-between; font-size: 11px; padding: 1px 0; }
        .summary-total { font-weight: bold; font-size: 13px; margin-top: 2px; }
        .summary-sub   { font-size: 10px; color: #333; }

        .badge {
            display: inline-block; font-size: 9px; font-weight: bold;
            border: 1px solid #000; padding: 0 3px; border-radius: 2px;
            text-transform: uppercase; letter-spacing: .5px;
        }

        .footer { text-align: center; font-size: 10px; margin-top: 6px; line-height: 1.5; }
        .footer-brand { font-weight: bold; font-size: 11px; margin-top: 4px; }

        .barcode-wrap { text-align: center; margin-top: 6px; border: 1px dashed #aaa; padding: 4px; border-radius: 2px; }
        .barcode-bars { font-family: monospace; font-size: 14px; letter-spacing: -1px; }
        .barcode-text { font-size: 9px; color: #555; }

        .saldo-box { background: #f0fdf4; border: 1px solid #bbf7d0; padding: 2px 4px; border-radius: 2px; }
        .saldo-label { color: #166534; }
        .saldo-val   { color: #15803d; font-weight: bold; }
    </style>
</head>
<body>

    {{-- ── Header toko ── --}}
    @if($showStoreName)
    <p class="store-name">{{ $storeName }}</p>
    @endif
    @if($showAddress && $storeAddress)
    <p class="store-info">{{ $storeAddress }}</p>
    @endif
    @if($showPhone && $storePhone)
    <p class="store-info">Telp: {{ $storePhone }}</p>
    @endif

    <div class="divider"></div>

    {{-- ── Info invoice ── --}}
    <table class="meta">
        @if($showInvoiceNumber)
        <tr>
            <td class="meta-label">Invoice</td>
            <td class="meta-value">{{ $order->invoice_number }}</td>
        </tr>
        @endif
        @if($showDate)
        <tr>
            <td class="meta-label">Tanggal</td>
            <td class="meta-value">{{ $order->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endif
        @if($showStudent && $order->customer_name)
        <tr>
            <td class="meta-label">Siswa</td>
            <td class="meta-value">{{ $order->customer_name }}</td>
        </tr>
        @endif
        @if($showPaymentMethod)
        <tr>
            <td class="meta-label">Bayar</td>
            <td class="meta-value">
                <span class="badge">{{ $order->payment_method === 'wallet' ? 'Dompet' : 'Tunai' }}</span>
            </td>
        </tr>
        @endif
        @if($showCashier && isset($cashierName))
        <tr>
            <td class="meta-label">Kasir</td>
            <td class="meta-value">{{ $cashierName }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    {{-- ── Item produk ── --}}

    @if($layout === 'compact')
        {{-- COMPACT: 1 baris per item --}}
        @foreach($order->items as $item)
        <div class="item-compact">
            <span class="item-compact-name">{{ $item->product->name }} ({{ $item->quantity }}×)</span>
            <span class="item-compact-price">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
        @endforeach

    @elseif($layout === 'detailed')
        {{-- DETAILED: tabel dengan kolom Qty dan Total --}}
        <table class="items-detailed">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="right" style="width:20px">Qty</th>
                    <th class="right" style="width:50px">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="td-qty">{{ $item->quantity }}</td>
                    <td class="td-total">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @if($showItemPrice)
                <tr>
                    <td colspan="3" class="item-detail">
                        Rp {{ number_format($item->price, 0, ',', '.') }} / pcs
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>

    @else
        {{-- STANDARD: nama produk + detail di baris kedua --}}
        <table class="items">
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td class="item-name">{{ $item->product->name }}</td>
                    @if($showSubtotalPerItem)
                    <td class="item-price">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    @endif
                </tr>
                @if($showItemPrice)
                <tr>
                    <td colspan="2" class="item-detail">
                        {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="divider"></div>

    {{-- ── Ringkasan pembayaran ── --}}
    <div class="summary-row summary-total">
        <span>TOTAL</span>
        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
    </div>

    {{-- Sisa saldo dompet --}}
    @if($showBalanceAfter && $order->payment_method === 'wallet' && isset($order->balance_after))
    <div class="summary-row summary-sub saldo-box" style="margin-top:3px;">
        <span class="saldo-label">Sisa Saldo</span>
        <span class="saldo-val">Rp {{ number_format($order->balance_after, 0, ',', '.') }}</span>
    </div>
    @endif

    {{-- Kembalian tunai --}}
    @if($showChange && $order->payment_method === 'cash' && $order->cash_amount)
    <div class="summary-row summary-sub">
        <span>Bayar</span>
        <span>Rp {{ number_format($order->cash_amount, 0, ',', '.') }}</span>
    </div>
    <div class="summary-row summary-sub">
        <span>Kembalian</span>
        <span>Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
    </div>
    @endif

    {{-- ── Footer ── --}}
    @if($showFooter && $storeFooter)
    <div class="divider"></div>
    <div class="footer">
        @foreach(explode("\n", $storeFooter) as $line)
            @if(trim($line))<p>{{ trim($line) }}</p>@endif
        @endforeach
        <p class="footer-brand">— {{ $storeName }} —</p>
    </div>
    @endif

    {{-- ── Barcode invoice ── --}}
    @if($showBarcode)
    <div class="barcode-wrap">
        <div class="barcode-bars">||| || ||| | || ||| ||</div>
        <div class="barcode-text">{{ $order->invoice_number }}</div>
    </div>
    @endif

</body>
</html>