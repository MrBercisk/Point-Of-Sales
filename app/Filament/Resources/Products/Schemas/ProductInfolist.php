<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category.name')
                ->label(__('app.category')),
                TextEntry::make('name')
                 ->label(__('app.name')),
                TextEntry::make('slug')
                    ->label(__('app.slug')),
                TextEntry::make('description')
                 ->label(__('app.description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('price')
                    ->label(__('app.price'))
                    ->money(),
                TextEntry::make('stock')
                    ->label(__('app.stock'))
                    ->numeric(),
                ImageEntry::make('image')
                     ->label(__('app.image'))
                     ->disk('public')
                    ->placeholder('-'),
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
