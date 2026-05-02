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
            margin-bottom: 4px;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
        }
        table.items td {
            vertical-align: top;
            padding: 1px 0;
        }
        .item-name  { width: 55%; }
        .item-qty   { width: 10%; text-align: center; }
        .item-price { width: 35%; text-align: right; }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 12px;
            margin-top: 2px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 6px;
        }
    </style>
</head>
<body>

    {{-- Header toko --}}
    <p class="store-name">{{ $storeName }}</p>
    <p class="store-info">{{ $storeAddress }}</p>
    <p class="store-info">Telp: {{ $storePhone }}</p>

    <div class="divider"></div>

    {{-- Info invoice --}}
    <table style="width:100%">
        <tr>
            <td>Invoice</td>
            <td class="right">{{ $order->invoice_number }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td class="right">{{ $order->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @if($order->customer_name)
        <tr>
            <td>Pelanggan</td>
            <td class="right">{{ $order->customer_name }}</td>
        </tr>
        @endif
        @if($order->customer_phone)
        <tr>
            <td>No. HP</td>
            <td class="right">{{ $order->customer_phone }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    {{-- Item produk --}}
    <table class="items">
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td class="item-name">{{ $item->product->name }}</td>
                <td class="item-qty">{{ $item->quantity }}</td>
                <td class="item-price">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2" style="font-size:10px; color:#333;">
                    Rp {{ number_format($item->price, 0, ',', '.') }} x{{ $item->quantity }}
                </td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    {{-- Total --}}
    <div class="total-row">
        <span>TOTAL</span>
        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
    </div>

    <div class="divider"></div>

    {{-- Footer --}}
    <div class="footer">
        <p>Terima kasih telah berbelanja!</p>
        <p>Barang yang sudah dibeli</p>
        <p>tidak dapat dikembalikan.</p>
        <br>
        <p>★ {{ $storeName }} ★</p>
    </div>

</body>
</html>