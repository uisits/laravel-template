<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('netid')
                    ->required()
                    ->maxLength(255),
                TextInput::make('uin')
                    ->required()
                    ->maxLength(9),
                TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Hidden::make('password')
                    ->default(fn () => Hash::make(Str::random(5))),
                CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->searchable(),
            ]);
    }
}
