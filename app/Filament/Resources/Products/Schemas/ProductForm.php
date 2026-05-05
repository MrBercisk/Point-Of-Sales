<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Group::make()
                ->schema([

                    Section::make(__('app.product_information'))
                        ->schema([
                            TextInput::make('name')
                                ->label(__('app.name'))
                                ->required()
                                ->maxLength(255)
                                ->placeholder(__('app.enter_name_product'))
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, $state, Set $set) {
                                    if ($operation !== 'create') return;
                                    $set('slug', Str::slug($state));
                                }),

                            FileUpload::make('image')
                                ->label(__('app.image'))
                                ->image()
                                ->disk('public')
                                ->directory('products')
                                ->imageEditor()
                                ->imageEditorAspectRatios(['1:1', '4:3', '16:9'])
                                ->maxSize(2048)
                                ->helperText('Max 2MB. Recommended: 500x500px'),

                            Section::make('Barcode')
                                ->schema([
                                    TextInput::make('barcode')
                                        ->label(__('app.barcode'))
                                        ->placeholder(__('app.barcode_placeholder'))
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->prefixIcon('heroicon-o-viewfinder-circle')
                                        ->helperText(__('app.barcode_helper'))
                                        ->suffixActions([
                                            ActionsAction::make('generateCode')
                                                ->icon('heroicon-o-arrow-path')
                                                ->tooltip(__('app.generate_code'))
                                                ->action(function (Get $get, Set $set) {
                                                    $symbology = $get('barcode_symbology') ?? 'Code 128';
                                                    $set('barcode', self::generateBarcodeNumber($symbology));
                                                }),
                                        ]),

                                    Select::make('barcode_symbology')
                                        ->label(__('app.barcode_symbology'))
                                        ->required()
                                        ->native(false)
                                        ->options([
                                            'Code 128' => 'Code 128',
                                            'Code 39'  => 'Code 39',
                                            'EAN-13'   => 'EAN-13',
                                            'EAN-8'    => 'EAN-8',
                                            'UPC-A'    => 'UPC-A',
                                            'UPC-E'    => 'UPC-E',
                                            'QR Code'  => 'QR Code',
                                        ])
                                        ->default('Code 128')
                                        ->live()
                                        ->helperText(__('app.barcode_symbology_helper')),

                                    Placeholder::make('barcode_preview')
                                        ->label('')
                                        ->columnSpanFull()
                                        ->visible(fn (Get $get): bool => filled($get('barcode')))
                                        ->content(fn (Get $get): HtmlString => self::renderBarcodePreview(
                                            $get('barcode') ?? '',
                                            $get('barcode_symbology') ?? 'Code 128',
                                        )),
                                ])
                                ->columns(2)
                                ->columnSpanFull(),

                            Select::make('category_id')
                                ->label(__('app.category'))
                                ->relationship('category', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->placeholder(__('app.choose_category'))
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, Set $set) =>
                                            $set('slug', Str::slug($state))
                                        ),
                                    TextInput::make('slug')
                                        ->required()
                                        ->maxLength(255),
                                ]),

                            Select::make('brand_id')
                                ->label(__('app.brand'))
                                ->relationship('brand', 'name')
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->placeholder(__('app.choose_brand'))
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),
                                ]),

                            TextInput::make('order_tax')
                                ->label(__('app.order_tax'))
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->default(0)
                                ->suffix('%'),

                            Select::make('tax_type')
                                ->label(__('app.tax_type'))
                                ->required()
                                ->native(false)
                                ->options([
                                    'Exclusive' => 'Exclusive',
                                    'Inclusive' => 'Inclusive',
                                ])
                                ->default('Exclusive'),

                            RichEditor::make('description')
                                ->label(__('app.description'))
                                ->placeholder(__('app.description_placeholder'))
                                ->maxLength(2000)
                                ->columnSpanFull()
                                ->toolbarButtons([
                                    'bold', 'italic', 'bulletList', 'orderedList',
                                ]),
                        ])
                        ->columns(2),

                    Section::make(__('app.pricing_inventory'))
                        ->schema([
                            Select::make('type')
                                ->label(__('app.type'))
                                ->required()
                                ->native(false)
                                ->options([
                                    'Standard Product' => __('app.standard_product'),
                                    'Service'          => __('app.service'),
                                    'Digital'          => __('app.digital'),
                                ])
                                ->default('Standard Product'),

                            TextInput::make('cost')
                                ->label(__('app.cost'))
                                ->required()
                                ->numeric()
                                ->prefix('Rp')
                                ->default(0)
                                ->placeholder(__('app.enter_product_cost')),

                            TextInput::make('price')
                                ->label(__('app.price'))
                                ->required()
                                ->numeric()
                                ->prefix('Rp')
                                ->maxValue(9999999999)
                                ->default(0)
                                ->placeholder(__('app.enter_product_price')),

                            Select::make('product_unit_id')
                                ->label(__('app.product_unit'))
                                ->relationship('productUnit', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->placeholder(__('app.choose_product_unit')),

                            Select::make('sale_unit_id')
                                ->label(__('app.sale_unit'))
                                ->relationship('saleUnit', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->placeholder(__('app.choose_sale_unit')),

                            Select::make('purchase_unit_id')
                                ->label(__('app.purchase_unit'))
                                ->relationship('purchaseUnit', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->native(false)
                                ->placeholder(__('app.choose_purchase_unit')),

                            TextInput::make('stock_alert')
                                ->label(__('app.stock_alert'))
                                ->numeric()
                                ->minValue(0)
                                ->default(0)
                                ->helperText(__('app.stock_alert_helper')),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(['lg' => 2]),

            Group::make()
                ->schema([
                    Section::make(__('app.status'))
                        ->schema([
                            Toggle::make('is_active')
                                ->label(__('app.active'))
                                ->default(true)
                                ->helperText(__('app.inactive_products_helper')),
                        ]),

                    Section::make('SEO')
                        ->schema([
                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->readOnlyOn('edit')
                                ->helperText(__('app.slug_helper')),
                        ]),
                ])
                ->columnSpan(['lg' => 1]),

        ])->columns(3);
    }

    /* ------------------------------------------------------------------ */
    /* Render barcode preview HTML                                          */
    /* ------------------------------------------------------------------ */

    private static function renderBarcodePreview(string $barcode, string $symbology): HtmlString
    {
        if (empty($barcode)) {
            return new HtmlString('');
        }

        $safeBarcode   = e($barcode);
        $safeSymbology = e($symbology);
        $isQr          = $symbology === 'QR Code';

        // ID unik agar tidak konflik saat Livewire re-render
        $uid = 'bc' . substr(md5($barcode . $symbology . microtime()), 0, 10);

        $jsFormat = match ($symbology) {
            'Code 39' => 'CODE39',
            'EAN-13'  => 'EAN13',
            'EAN-8'   => 'EAN8',
            'UPC-A'   => 'UPC',
            'UPC-E'   => 'UPCE',
            default   => 'CODE128',
        };

        if ($isQr) {
            return new HtmlString(<<<HTML
            <div class="flex flex-col items-center gap-3 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">Preview — {$safeSymbology}</span>
                <div id="{$uid}" class="rounded-lg bg-white p-2 shadow"></div>
                <span class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-300">{$safeBarcode}</span>
            </div>
            <script>
            (function waitForQR() {
                if (typeof QRCode === 'undefined') {
                    return setTimeout(waitForQR, 80);
                }
                var el = document.getElementById('{$uid}');
                if (!el || el.dataset.rendered) return;
                el.dataset.rendered = '1';
                new QRCode(el, {
                    text:   '{$safeBarcode}',
                    width:  160,
                    height: 160,
                    correctLevel: QRCode.CorrectLevel.M,
                });
            })();
            </script>
            HTML);
        }

        return new HtmlString(<<<HTML
        <div class="flex flex-col items-center gap-3 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <span class="text-xs font-semibold uppercase tracking-widest text-gray-400">Preview — {$safeSymbology}</span>
            <div id="{$uid}-wrap" class="w-full flex justify-center">
                <svg id="{$uid}" class="max-w-xs rounded bg-white p-1"></svg>
            </div>
            <p id="{$uid}-err" class="hidden rounded bg-red-50 px-3 py-1 text-xs text-red-500 dark:bg-red-900/20">
                ⚠ Kode tidak valid untuk format {$safeSymbology}.
            </p>
            <span class="rounded bg-gray-100 px-2 py-0.5 font-mono text-xs text-gray-600 dark:bg-gray-800 dark:text-gray-300">{$safeBarcode}</span>
        </div>
        <script>
        (function waitForJsBarcode() {
            if (typeof JsBarcode === 'undefined') {
                return setTimeout(waitForJsBarcode, 80);
            }
            var svgEl = document.getElementById('{$uid}');
            if (!svgEl || svgEl.dataset.rendered) return;
            svgEl.dataset.rendered = '1';
            try {
                JsBarcode(svgEl, '{$safeBarcode}', {
                    format:       '{$jsFormat}',
                    lineColor:    '#1a1a1a',
                    background:   '#ffffff',
                    width:        2,
                    height:       70,
                    displayValue: true,
                    fontSize:     14,
                    margin:       10,
                });
                document.getElementById('{$uid}-wrap').classList.remove('hidden');
            } catch (err) {
                document.getElementById('{$uid}-wrap').classList.add('hidden');
                var errEl = document.getElementById('{$uid}-err');
                if (errEl) errEl.classList.remove('hidden');
            }
        })();
        </script>
        HTML);
    }

    /* ------------------------------------------------------------------ */
    /* Generate barcode number sesuai symbology                            */
    /* ------------------------------------------------------------------ */

    private static function generateBarcodeNumber(string $symbology): string
    {
        return match ($symbology) {
            'EAN-13'  => self::generateEan13(),
            'EAN-8'   => self::generateEan8(),
            'UPC-A'   => self::generateUpcA(),
            'UPC-E'   => self::generateUpcE(),
            'Code 39' => strtoupper(Str::random(10)),
            'QR Code' => (string) Str::uuid(),
            default   => self::generateCode128(),
        };
    }

    private static function generateEan13(): string
    {
        $d = '';
        for ($i = 0; $i < 12; $i++) $d .= random_int(0, 9);
        $sum = 0;
        for ($i = 0; $i < 12; $i++) $sum += (int)$d[$i] * ($i % 2 === 0 ? 1 : 3);
        return $d . ((10 - ($sum % 10)) % 10);
    }

    private static function generateEan8(): string
    {
        $d = '';
        for ($i = 0; $i < 7; $i++) $d .= random_int(0, 9);
        $sum = 0;
        for ($i = 0; $i < 7; $i++) $sum += (int)$d[$i] * ($i % 2 === 0 ? 3 : 1);
        return $d . ((10 - ($sum % 10)) % 10);
    }

    private static function generateUpcA(): string
    {
        $d = '';
        for ($i = 0; $i < 11; $i++) $d .= random_int(0, 9);
        $sum = 0;
        for ($i = 0; $i < 11; $i++) $sum += (int)$d[$i] * ($i % 2 === 0 ? 3 : 1);
        return $d . ((10 - ($sum % 10)) % 10);
    }

    private static function generateUpcE(): string
    {
        $d = '';
        for ($i = 0; $i < 6; $i++) $d .= random_int(0, 9);
        return $d;
    }

    private static function generateCode128(): string
    {
        $d = '';
        for ($i = 0; $i < 13; $i++) $d .= random_int(0, 9);
        return $d;
    }
}