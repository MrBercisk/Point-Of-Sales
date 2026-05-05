<?php

namespace App\Filament\Resources\Transactions\WalletTransactionResource\Schemas;

use App\Models\Student;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WalletTransactionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.class')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'top_up'     => 'Top Up',
                        'purchase'   => 'Belanja',
                        'refund'     => 'Refund',
                        'adjustment' => 'Penyesuaian',
                        default      => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'top_up'     => 'success',
                        'purchase'   => 'warning',
                        'refund'     => 'info',
                        'adjustment' => 'gray',
                        default      => 'gray',
                    }),

                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('balance_before')
                    ->label('Saldo Sebelum')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('balance_after')
                    ->label('Saldo Sesudah')
                    ->money('IDR'),

                TextColumn::make('reference')
                    ->label('Referensi')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Petugas')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('note')
                    ->label('Catatan')
                    ->default('-')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([

                /* Filter tipe transaksi */
                SelectFilter::make('type')
                    ->label('Tipe Transaksi')
                    ->options([
                        'top_up'     => 'Top Up',
                        'purchase'   => 'Belanja',
                        'refund'     => 'Refund',
                        'adjustment' => 'Penyesuaian',
                    ]),

                /* Filter kelas */
                SelectFilter::make('class')
                    ->label('Kelas')
                    ->options(
                        fn () => Student::distinct()
                            ->orderBy('class')
                            ->pluck('class', 'class')
                            ->toArray()
                    )
                    ->query(
                        fn (Builder $query, array $data) =>
                            $query->when(
                                $data['value'],
                                fn ($q) => $q->whereHas(
                                    'student',
                                    fn ($q) => $q->where('class', $data['value'])
                                )
                            )
                    ),

                /* Filter hari ini */
                Filter::make('today')
                    ->label('Hari Ini')
                    ->query(fn (Builder $q) => $q->whereDate('created_at', today())),

                /* Filter rentang tanggal */
                Filter::make('date_range')
                    ->label('Rentang Tanggal')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari Tanggal')
                            ->native(false),
                        DatePicker::make('until')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn ($q) => $q->whereDate('created_at', '>=', $data['from'])
                            )
                            ->when(
                                $data['until'],
                                fn ($q) => $q->whereDate('created_at', '<=', $data['until'])
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'])  $indicators[] = 'Dari: ' . $data['from'];
                        if ($data['until']) $indicators[] = 'Sampai: ' . $data['until'];
                        return $indicators;
                    }),
            ])
            ->filtersLayout(\Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->poll('60s'); // auto refresh tiap 1 menit
    }
}