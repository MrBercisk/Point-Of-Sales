<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* KIRI — data utama */
            Group::make()
                ->schema([
                    Section::make('Data Siswa')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Masukkan nama siswa'),

                            TextInput::make('nisn')
                                ->label('NISN')
                                ->unique(ignoreRecord: true)
                                ->maxLength(20)
                                ->placeholder('Nomor Induk Siswa Nasional'),

                            Select::make('class')
                                ->label('Kelas')
                                ->required()
                                ->native(false)
                                ->options(self::classOptions())
                                ->placeholder('Pilih kelas'),

                            Select::make('gender')
                                ->label('Jenis Kelamin')
                                ->required()
                                ->native(false)
                                ->options([
                                    'L' => 'Laki-laki',
                                    'P' => 'Perempuan',
                                ]),
                        ])
                        ->columns(2),

                    Section::make('Data Orang Tua')
                        ->schema([
                            TextInput::make('parent_name')
                                ->label('Nama Orang Tua')
                                ->maxLength(255)
                                ->placeholder('Nama ayah / ibu'),

                            TextInput::make('parent_phone')
                                ->label('No. WhatsApp Orang Tua')
                                ->tel()
                                ->maxLength(20)
                                ->placeholder('08xxxxxxxxxx')
                                ->helperText('Untuk notifikasi saldo & transaksi'),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(['lg' => 2]),

            /* KANAN — foto, saldo, status */
            Group::make()
                ->schema([
                    Section::make('Foto Siswa')
                        ->schema([
                            FileUpload::make('photo')
                                ->label('')
                                ->image()
                                ->disk('public')
                                ->directory('students')
                                ->imageEditor()
                                ->imageResizeMode('cover')
                                ->imageCropAspectRatio('1:1')
                                ->imageResizeTargetWidth('300')
                                ->imageResizeTargetHeight('300')
                                ->maxSize(1024)
                                ->helperText('Foto 3x4, maks 1MB'),
                        ]),

                    Section::make('Kartu Siswa')
                        ->schema([
                            TextInput::make('barcode')
                                ->label('Kode Barcode Kartu')
                                ->unique(ignoreRecord: true)
                                ->maxLength(100)
                                ->placeholder('Scan atau isi manual')
                                ->prefixIcon('heroicon-o-viewfinder-circle')
                                ->helperText('Opsional — untuk scan kartu siswa di kasir'),
                        ]),

                    Section::make('Status')
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Siswa Aktif')
                                ->default(true)
                                ->helperText('Non-aktifkan jika siswa sudah lulus atau keluar'),
                        ]),
                ])
                ->columnSpan(['lg' => 1]),

        ])->columns(3);
    }

    /** Daftar kelas SD kelas 1–6 dengan rombel A–D */
    private static function classOptions(): array
    {
        $options = [];
        foreach (range(1, 6) as $grade) {
            foreach (['A', 'B', 'C', 'D'] as $rombel) {
                $label = "Kelas {$grade}{$rombel}";
                $options["{$grade}{$rombel}"] = $label;
            }
        }
        return $options;
    }
}