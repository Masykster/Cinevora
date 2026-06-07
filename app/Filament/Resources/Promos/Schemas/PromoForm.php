<?php

namespace App\Filament\Resources\Promos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                FileUpload::make('image_path')
                    ->image()
                    ->disk('supabase')
                    ->directory('promos')
                    ->required()
                    ->saveUploadedFileUsing(fn ($file) => \App\Helpers\ImageHelper::storeAsWebp($file, 'promos', 'supabase')),
                TextInput::make('link_url')
                    ->url(),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
