<?php

namespace App\Filament\Resources\Topup\Schemas;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class TopUpTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.class')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Nominal Top Up')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('balance_before')
                    ->label('Saldo Sebelum')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('balance_after')
                    ->label('Saldo Sesudah')
                    ->money('IDR'),

                TextColumn::make('user.name')
                    ->label('Petugas')
                    ->default('-'),

                TextColumn::make('note')
                    ->label('Catatan')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('student_class')
                    ->label('Kelas')
                    ->relationship('student', 'class')
                    ->searchable(),

                Filter::make('today')
                    ->label('Hari ini')
                    ->query(fn (Builder $q) => $q->whereDate('created_at', today()))
                    ->default(),

                Filter::make('date_range')
                    ->label('Rentang tanggal')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $q, array $data) {
                        return $q
                            ->when($data['from'],  fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->poll('30s'); // auto refresh tiap 30 detik
    }
}