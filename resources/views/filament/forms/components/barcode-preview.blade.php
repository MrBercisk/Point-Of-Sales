
@php
    $barcode    = $getState() ?? '';           // nilai field 'barcode'
    $symbology  = $getRecord()?->barcode_symbology
                    ?? request()->input('data.barcode_symbology')
                    ?? 'Code 128';
    $isQr       = $symbology === 'QR Code';
    $uniqueId   = 'barcode-' . md5($barcode . $symbology);

    $jsBarcode  = match ($symbology) {
        'Code 39'  => 'CODE39',
        'EAN-13'   => 'EAN13',
        'EAN-8'    => 'EAN8',
        'UPC-A'    => 'UPC',
        'UPC-E'    => 'UPCE',
        default    => 'CODE128',   // Code 128 & fallback
    };
@endphp

@if ($barcode)
    <div
        x-data="{
            barcode:   @js($barcode),
            symbology: @js($symbology),
            isQr:      @js($isQr),
            jsFormat:  @js($jsBarcode),
            uniqueId:  @js($uniqueId),
        }"
        x-init="
            $nextTick(() => {
                if (isQr) {
                    // Render QR Code
                    new QRCode(document.getElementById(uniqueId), {
                        text:   barcode,
                        width:  160,
                        height: 160,
                    });
                } else {
                    // Render Barcode 1D
                    JsBarcode('#' + uniqueId, barcode, {
                        format:      jsFormat,
                        lineColor:   '#000',
                        width:       2,
                        height:      60,
                        displayValue: true,
                        fontSize:    14,
                    });
                }
            })
        "
        class="flex flex-col items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900"
    >
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
            Preview — {{ $symbology }}
        </p>

        @if ($isQr)
            {{-- QR Code: div target untuk QRCode.js --}}
            <div id="{{ $uniqueId }}" class="rounded bg-white p-2 shadow-sm"></div>
        @else
            {{-- Barcode 1D: svg target untuk JsBarcode --}}
            <svg id="{{ $uniqueId }}" class="rounded bg-white p-2 shadow-sm"></svg>
        @endif
    </div>

    {{-- Load library hanya sekali --}}
    @once
        @push('scripts')
            {{-- JsBarcode untuk barcode 1D --}}
            <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

            {{-- QRCode.js untuk QR Code --}}
            <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
        @endpush
    @endonce
@endif