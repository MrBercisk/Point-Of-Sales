<?php

namespace App\Filament\Resources\UnitResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('app.unit_information'))
                ->schema([
                    TextInput::make('name')
                         ->label(__('app.unit_name'))
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g. Pieces, Box, Kilogram'),

                    TextInput::make('short_name')
                         ->label(__('app.short_name'))
                        ->required()
                        ->maxLength(50)
                        ->placeholder('e.g. Pcs, Box, Kg'),
                ])
                ->columns(2),
        ]);
    }
}