<?php

namespace App\Filament\Resources\Vouchers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VoucherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('description'),
                Select::make('type')
                    ->options(['percentage' => 'Percentage (%)', 'fixed' => 'Fixed Amount'])
                    ->required(),
                TextInput::make('value')
                    ->required()
                    ->numeric(),
                Select::make('target')
                    ->options(['ticket' => 'Ticket Only', 'fnb' => 'F&B Only', 'all' => 'All Items'])
                    ->default('all')
                    ->required(),
                TextInput::make('quota')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                TextInput::make('used_count')
                    ->disabled()
                    ->dehydrated(false)
                    ->numeric()
                    ->default(0),
                TextInput::make('min_purchase')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                TextInput::make('max_discount')
                    ->numeric()
                    ->prefix('Rp'),
                DatePicker::make('valid_from')
                    ->required(),
                DatePicker::make('valid_until')
                    ->required(),
                Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }
}
