<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Category Information')
                ->description('Manage product categories')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, $state, Set $set) {
                            if ($operation !== 'create') return;
                            $set('slug', Str::slug($state));
                        }),

                   TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Auto-generated from name'),

                   Textarea::make('description')
                        ->maxLength(500)
                        ->rows(3)
                        ->columnSpanFull(),

                   Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ])->columns(1);
    }
}
