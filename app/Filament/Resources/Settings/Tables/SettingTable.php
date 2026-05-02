<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('CompanyName')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('CompanyPhone')
                    ->label('Phone')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('CompanyAdress')
                    ->label('Address')
                    ->limit(40)
                    ->toggleable(),

                TextColumn::make('default_language')
                    ->label('Language')
                    ->badge()
                    ->color('info'),

                IconColumn::make('whatsapp_enabled')
                    ->label('WA Enabled')
                    ->boolean(),

                IconColumn::make('show_language')
                    ->label('Lang Switcher')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }
}