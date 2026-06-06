<?php

namespace App\Filament\Resources\Cafe\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                        $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null
                    ),
                TextInput::make('slug')
                    ->required()
                    ->unique('categories', 'slug', ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('icon')
                    ->maxLength(255)
                    ->placeholder('Contoh: 🍿 atau 🥤'),
                Toggle::make('is_active')
                    ->label('Aktif / Muncul di Web')
                    ->default(true)
                    ->required(),
            ]);
    }
}
