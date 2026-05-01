<?php

namespace App\Filament\Resources\BrandResource\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Brand Information')
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
                        ->readOnlyOn('edit')
                        ->helperText('Auto-generated from name'),

                    FileUpload::make('image')
                        ->label('Brand Logo')
                        ->image()
                        ->disk('public')
                        ->directory('brands')
                        ->imageEditor()
                        ->maxSize(1024)
                        ->helperText('Max 1MB')
                        ->columnSpanFull(),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }
}