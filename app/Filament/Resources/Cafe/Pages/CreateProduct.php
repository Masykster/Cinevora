<?php

namespace App\Filament\Resources\Cafe\Pages;

use App\Filament\Resources\Cafe\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
