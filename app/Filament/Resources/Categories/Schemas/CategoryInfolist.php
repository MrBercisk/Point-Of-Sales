<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                ->label(__('app.name')),
                TextEntry::make('slug')
                    ->label(__('app.slug')),
                TextEntry::make('description')
                    ->label(__('app.description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->label(__('app.active'))
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label(__('app.created_at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('app.updated_at'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
