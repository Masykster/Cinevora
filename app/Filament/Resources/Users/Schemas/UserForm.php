<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                Select::make('role')
                    ->options([
                        'user' => 'User',
                        'cinema_admin' => 'Cinema Admin',
                        'cafe_admin' => 'Cafe Admin',
                    ])
                    ->default('user')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(20),
                \Filament\Forms\Components\FileUpload::make('avatar')
                    ->image()
                    ->disk('public')
                    ->directory('users/avatars')
                    ->maxSize(1024),
            ]);
    }
}
