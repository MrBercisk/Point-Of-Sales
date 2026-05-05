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
            Section::make(__('app.category_information'))
                ->description(__('app.manage_product_categories'))
                ->schema([
                    TextInput::make('name')
                        ->label(__('app.name'))
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, $state, Set $set) {
                            if ($operation !== 'create') return;
                            $set('slug', Str::slug($state));
                        }),

                    TextInput::make('slug')
                        ->label(__('app.slug'))
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText(__('app.slug_helper')),

                    Textarea::make('description')
                        ->label(__('app.description'))
                        ->maxLength(500)
                        ->rows(3)
                        ->columnSpanFull(),

                    Toggle::make('is_active')
                        ->label(__('app.active'))
                        ->default(true),
                ])
                ->columns(2)
                ->columnSpanFull(),
        ])->columns(1);
    }
}