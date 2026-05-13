<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            ComponentsSection::make('Informasi User')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->revealable()
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $operation) => $operation === 'create')
                        ->placeholder(fn (string $operation) => $operation === 'edit' ? 'Kosongkan jika tidak diubah' : null)
                        ->maxLength(255),
                ])
                ->columns(2),

            ComponentsSection::make('Role & Akses')
                ->schema([
                    Select::make('roles')
                        ->label('Role')
                        ->relationship('roles', 'name')
                        ->options(Role::all()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->multiple()        // bisa multi role
                        ->required(),
                ]),
        ]);
    }
}