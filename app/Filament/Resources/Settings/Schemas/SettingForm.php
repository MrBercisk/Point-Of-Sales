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
        return $schema->components([

            // ── General ──────────────────────────────────────────────────────
            Section::make('General')
                ->description('Informasi umum perusahaan / toko')
                ->schema([
                    TextInput::make('CompanyName')
                        ->label('Company Name')
                        ->required()
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-building-office'),

                    TextInput::make('CompanyPhone')
                        ->label('Company Phone')
                        ->required()
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-phone'),

                    TextInput::make('email')
                        ->label('Default Email')
                        ->email()
                        ->required()
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-envelope'),

                    TextInput::make('CompanyAdress')
                        ->label('Address')
                        ->required()
                        ->maxLength(191)
                        ->columnSpan(2)
                        ->prefixIcon('heroicon-o-map-pin'),

                    TextInput::make('developed_by')
                        ->label('Developed By')
                        ->required()
                        ->maxLength(192)
                        ->prefixIcon('heroicon-o-code-bracket'),

                    Select::make('default_language')
                        ->label('Default Language')
                        ->options([
                            'en'    => '🇬🇧 English',
                            'id'    => '🇮🇩 Indonesian',
                            'fr'    => '🇫🇷 Français',
                            'ar'    => '🇸🇦 العربية',
                            'tr'    => '🇹🇷 Turkish',
                            'th'    => '🇹🇭 Thai',
                            'hi'    => '🇮🇳 Hindi',
                            'de'    => '🇩🇪 German',
                            'es'    => '🇪🇸 Spanish',
                            'it'    => '🇮🇹 Italian',
                            'zh'    => '🇨🇳 Simplified Chinese',
                            'zh-TW' => '🇹🇼 Traditional Chinese',
                        ])
                        ->required()
                        ->prefixIcon('heroicon-o-language'),

                    Toggle::make('show_language')
                        ->label('Show Language Switcher')
                        ->inline(false),

                    Toggle::make('quotation_with_stock')
                        ->label('Quotation With Stock')
                        ->inline(false),

                    TextInput::make('footer')
                        ->label('Footer Text')
                        ->required()
                        ->maxLength(192)
                        ->columnSpanFull()
                        ->prefixIcon('heroicon-o-document-text'),

                    FileUpload::make('logo')
                        ->label('Company Logo')
                        ->image()
                        ->disk('public')
                        ->directory('logos')
                        ->imageEditor()
                        ->maxSize(2048)
                        ->columnSpanFull(),
                ])
                ->columns(3),

            // ── Invoice ───────────────────────────────────────────────────────
            Section::make('Invoice')
                ->description('Pengaturan footer invoice')
                ->schema([
                    Toggle::make('is_invoice_footer')
                        ->label('Tampilkan Footer di Invoice')
                        ->live()
                        ->inline(false),

                    Textarea::make('invoice_footer')
                        ->label('Invoice Footer Text')
                        ->rows(3)
                        ->maxLength(192)
                        ->visible(fn ($get) => $get('is_invoice_footer'))
                        ->columnSpanFull(),
                ])
                ->columns(2),

            // ── POS & Struk ───────────────────────────────────────────────────
            Section::make('POS & Struk')
                ->description('Informasi yang tampil di struk kasir')
                ->schema([
                    TextInput::make('receipt_store_name')
                        ->label('Nama Toko di Struk')
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-building-storefront'),

                    TextInput::make('receipt_store_phone')
                        ->label('Telepon di Struk')
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-phone'),

                    TextInput::make('receipt_store_address')
                        ->label('Alamat di Struk')
                        ->maxLength(191)
                        ->prefixIcon('heroicon-o-map-pin'),

                    Textarea::make('receipt_footer')
                        ->label('Footer Struk')
                        ->rows(2)
                        ->maxLength(255)
                        ->placeholder('Terima kasih telah berbelanja!')
                        ->columnSpanFull(),
                ])
                ->columns(3),

            // ── WhatsApp ──────────────────────────────────────────────────────
            Section::make('WhatsApp (Fonnte)')
                ->description('Konfigurasi pengiriman struk via WhatsApp')
                ->schema([
                    Toggle::make('whatsapp_enabled')
                        ->label('Aktifkan Kirim WA')
                        ->live()
                        ->inline(false)
                        ->columnSpanFull(),

                    TextInput::make('whatsapp_token')
                        ->label('Device Token Fonnte')
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
                ->columns(2),

        ]);
    }
}