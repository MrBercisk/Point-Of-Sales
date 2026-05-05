<?php

namespace App\Filament\Resources\Topup\Schemas;

use App\Models\Student;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class TopUpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Informasi Siswa')
                ->columns(2)
                ->schema([

                    Select::make('student_id')
                        ->label('Siswa')
                        ->searchable()
                        ->required()
                         ->live()
                        ->options([]) 
                        ->getSearchResultsUsing(fn (string $search): array =>
                            Student::query()
                                ->where('name', 'like', "%{$search}%")
                                ->limit(5)
                                ->pluck('name', 'id')
                                ->toArray()
                        )
                        ->getOptionLabelUsing(fn ($value): ?string =>
                            Student::where('id', $value)->value('name')
                        ),
                        
                    Placeholder::make('saldo')
                        ->label('Saldo Saat Ini')
                        ->content(function (Get $get): HtmlString {

                            $id = $get('student_id');

                            if (!$id) {
                                return new HtmlString('<span class="text-gray-400">— pilih siswa —</span>');
                            }

                            // query saat render
                            $balance = Student::where('id', $id)->value('balance') ?? 0;

                            return new HtmlString(
                                '<strong style="font-size:1.3rem;color:#1D9E75">Rp '
                                . number_format($balance, 0, ',', '.')
                                . '</strong>'
                            );
                        }),
                ]),

            Section::make('Detail Top Up')
                ->columns(2)
                ->schema([

                    TextInput::make('amount')
                        ->label('Nominal Top Up')
                        ->numeric()
                         ->live()
                        ->required()
                        ->minValue(1000)
                        ->step(1000)
                        ->prefix('Rp'),

                    Placeholder::make('after')
                        ->label('Saldo Setelah Top Up')
                         ->live()
                        ->content(function (Get $get): HtmlString {

                            $id     = $get('student_id');
                            $amount = (float) ($get('amount') ?? 0);

                            if (!$id || $amount <= 0) {
                                return new HtmlString('<span class="text-gray-400">— isi data —</span>');
                            }

                            $balance = Student::where('id', $id)->value('balance') ?? 0;

                            return new HtmlString(
                                '<strong style="font-size:1.3rem;color:#1D9E75">Rp '
                                . number_format($balance + $amount, 0, ',', '.')
                                . '</strong>'
                            );
                        }),

                    Actions::make([
                        Action::make('5rb')
                            ->label('Rp 5.000')
                            ->action(fn ($set) => $set('amount', 5000)),

                        Action::make('10rb')
                            ->label('Rp 10.000')
                            ->action(fn ($set) => $set('amount', 10000)),

                        Action::make('20rb')
                            ->label('Rp 20.000')
                            ->action(fn ($set) => $set('amount', 20000)),

                        Action::make('50rb')
                            ->label('Rp 50.000')
                            ->action(fn ($set) => $set('amount', 50000)),
                    ])->columnSpanFull(),

                    Select::make('user_id')
                        ->label('Petugas')
                        ->options(fn () => User::limit(20)->pluck('name', 'id'))
                        ->required()
                        ->default(fn () => Filament::auth()->id()),

                    Textarea::make('note')
                        ->label('Catatan')
                        ->rows(3),
                ]),
        ]);
    }
}