<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Models\Product;
use App\Models\Student;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\BulkActionGroup as ActionsBulkActionGroup;
use Filament\Actions\DeleteBulkAction as ActionsDeleteBulkAction;
use Filament\Actions\EditAction as ActionsEditAction;
use Filament\Actions\ViewAction as ActionsViewAction;
use Filament\Facades\Filament;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StudentTable
{
    public static function configure(Table $table): Table
    {
        $minSaldo = Product::min('price') ?? 0;
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(fn (Student $r) => 'https://ui-avatars.com/api/?name=' . urlencode($r->name) . '&background=1D9E75&color=fff'),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('class')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('gender')
                    ->label('L/P')
                    ->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan')
                    ->badge()
                    ->color(fn ($state) => $state === 'L' ? 'info' : 'pink')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('balance')
                    ->label('Saldo')
                    ->money('IDR')
                    ->sortable()
                    ->color(fn ($state) => $state <= $minSaldo ? 'danger' : 'success'),

                TextColumn::make('parent_phone')
                    ->label('WA Ortu')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('class')
            ->filters([
                SelectFilter::make('class')
                    ->label('Kelas')
                    ->options(fn () => Student::distinct()->pluck('class', 'class')->sort()->toArray()),

                SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options(['L' => 'Laki-laki', 'P' => 'Perempuan']),

                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),

                TernaryFilter::make('low_balance')
                    ->label('Saldo Rendah (≤ Rp5.000)')
                    ->queries(
                        true: fn ($q) => $q->where('balance', '<=', $minSaldo),
                        false: fn ($q) => $q->where('balance', '>', $minSaldo),
                    ),
            ])
            ->actions([
                /* Tombol top up langsung dari tabel */
                // ActionsAction::make('topUp')
                //     ->label('Top Up')
                //     ->icon('heroicon-o-plus-circle')
                //     ->color('success')
                //     ->form([
                //         \Filament\Forms\Components\TextInput::make('amount')
                //             ->label('Nominal Top Up')
                //             ->required()
                //             ->numeric()
                //             ->minValue(1000)
                //             ->prefix('Rp')
                //             ->placeholder('Masukkan nominal'),

                //         \Filament\Forms\Components\Textarea::make('note')
                //             ->label('Catatan')
                //             ->placeholder('Opsional')
                //             ->rows(2),
                //     ])
                //     ->action(function (Student $record, array $data): void {
                //         $record->topUp(
                //             amount: (float) $data['amount'],
                //             userId: Filament::auth()->id(),
                //             note: $data['note'] ?? null,
                //         );
                //     })
                //     ->successNotificationTitle('Saldo berhasil ditambahkan'),

                ActionsViewAction::make(),
                ActionsEditAction::make(),
            ])
            ->bulkActions([
                ActionsBulkActionGroup::make([
                    ActionsDeleteBulkAction::make(),
                ]),
            ]);
    }
}