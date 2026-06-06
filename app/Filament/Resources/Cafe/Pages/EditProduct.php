<?php

namespace App\Filament\Resources\Cafe\Pages;

use App\Filament\Resources\Cafe\ProductResource;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;
}
