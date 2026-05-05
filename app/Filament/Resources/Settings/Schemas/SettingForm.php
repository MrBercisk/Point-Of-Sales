<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema

            // ── General ──────────────────────────────────────────────────────
           ->columns(3)  // ← tambahkan ini
            ->components([

            // ── General ──────────────────────────────────────────────────────
            Section::make(__('app.general'))
                ->description(__('app.general_information'))
                ->schema([
                    TextInput::make('CompanyName')
                        ->label(__('app.company_name'))
                        ->required()
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-building-office')
                        ->columnSpan(2),

                    TextInput::make('CompanyPhone')
                         ->label(__('app.company_phone'))
                        ->required()
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-phone'),

                    TextInput::make('email')
                         ->label(__('app.email'))
                        ->email()
                        ->required()
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-envelope'),

                    TextInput::make('CompanyAdress')
                         ->label(__('app.address'))
                        ->required()
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-map-pin')
                        ->columnSpan(2),

                    TextInput::make('developed_by')
                        ->label('Developed By')
                        ->required()
                        ->maxLength(192)
                        ->prefixIcon('heroicon-o-code-bracket'),

                    Select::make('default_language')
                         ->label(__('app.default_language'))
                        ->options([
                            'en'    => '🇬🇧 English',
                            'Ind'    => '🇮🇩 Indonesian',
                            'kr'    => '🇰🇷 Korean',
                            // 'fr'    => '🇫🇷 Français',
                            // 'ar'    => '🇸🇦 العربية',
                            // 'tr'    => '🇹🇷 Turkish',
                            // 'th'    => '🇹🇭 Thai',
                            // 'hi'    => '🇮🇳 Hindi',
                            // 'de'    => '🇩🇪 German',
                            // 'es'    => '🇪🇸 Spanish',
                            // 'it'    => '🇮🇹 Italian',
                            // 'zh'    => '🇨🇳 Simplified Chinese',
                            // 'zh-TW' => '🇹🇼 Traditional Chinese',
                        ])
                        ->required()
                        ->prefixIcon('heroicon-o-language'),

                    Toggle::make('show_language')
                        ->label(__('app.show_language'))
                        ->inline(false),

                    // Toggle::make('quotation_with_stock')
                    //     ->label(__('app.quotation_with_stock'))
                    //     ->inline(false),

                    TextInput::make('footer')
                        ->label(__('app.footer'))
                        ->required()
                        ->maxLength(192)
                        ->columnSpanFull()
                        ->prefixIcon('heroicon-o-document-text'),

                    FileUpload::make('logo')
                        ->label(__('app.logo'))
                        ->image()
                        ->disk('public')
                        ->directory('logos')
                        ->imageEditor()
                        ->maxSize(2048)
                        ->columnSpanFull(),
                ])
                ->columns(3)
                ->columnSpan(2),  // General = 2/3 lebar

            // ── Invoice ───────────────────────────────────────────────────────
            Section::make('Invoice')
                ->description(__('app.invoice_footer_setting'))
                ->schema([
                    Toggle::make('is_invoice_footer')
                        ->label(__('app.is_invoice_footer'))
                        ->live()
                        ->inline(false),

                    Textarea::make('invoice_footer')
                        ->label(__('app.invoice_footer'))
                        ->rows(3)
                        ->maxLength(192)
                        ->visible(fn ($get) => $get('is_invoice_footer'))
                        ->columnSpanFull(),
                ])
                ->columns(1)
                ->columnSpan(1),  // Invoice = 1/3 lebar

            // ── POS & Struk ───────────────────────────────────────────────────
            Section::make('POS & Struk')
                ->description(__('app.information_struk_helper'))
                ->schema([
                    TextInput::make('receipt_store_name')
                        ->label(__('app.receipt_store_name'))
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-building-storefront'),

                    TextInput::make('receipt_store_phone')
                        ->label(__('app.receipt_store_phone'))
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-phone'),

                    TextInput::make('receipt_store_address')
                        ->label(__('app.receipt_store_address'))
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-map-pin'),

                    Textarea::make('receipt_footer')
                        ->label(__('app.receipt_footer'))
                        ->rows(2)
                        ->maxLength(255)
                        ->placeholder('Terima kasih telah berbelanja!')
                        ->columnSpanFull(),
                ])
                ->columns(3)
                ->columnSpan('full'),  // ← full width

            // ── WhatsApp ──────────────────────────────────────────────────────
            Section::make('WhatsApp (Fonnte)')
                ->description(__('app.whatsapp_desc'))
                ->schema([
                    Toggle::make('whatsapp_enabled')
                        ->label(__('app.whatsapp_enabled'))
                        ->live()
                        ->inline(false)
                        ->columnSpanFull(),

                    TextInput::make('whatsapp_token')
                        ->label(__('app.whatsapp_token'))
                        ->password()
                        ->revealable()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-key')
                        ->visible(fn ($get) => $get('whatsapp_enabled'))
                        ->helperText('Dapatkan token dari dashboard fonnte.com → Device → Token'),

                    TextInput::make('whatsapp_base_url')
                        ->label('Base URL API')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-globe-alt')
                        ->default('https://api.fonnte.com')
                        ->visible(fn ($get) => $get('whatsapp_enabled')),
                ])
                ->columns(2)
                ->columnSpan('full'), 
        ]);
    }
}