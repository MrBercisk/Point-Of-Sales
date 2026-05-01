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
            Section::make('Unit Information')
                ->schema([
                    TextInput::make('name')
                        ->label('Unit Name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g. Pieces, Box, Kilogram'),

                    TextInput::make('short_name')
                        ->label('Short Name')
                        ->required()
                        ->maxLength(50)
                        ->placeholder('e.g. Pcs, Box, Kg'),
                ])
                ->columns(2),
        ]);
    }
}