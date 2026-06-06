<?php

namespace App\Filament\Resources\Cafe\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->minValue(0),
                TextInput::make('stock')
                    ->numeric()
                    ->helperText('Isi 0 untuk stok tidak terbatas (Unlimited).')
                    ->default(0)
                    ->required()
                    ->minValue(0),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('cafe/products')
                    ->maxSize(1024),
                Toggle::make('is_available')
                    ->label('Tersedia')
                    ->default(true)
                    ->required(),
            ]);
    }
}
