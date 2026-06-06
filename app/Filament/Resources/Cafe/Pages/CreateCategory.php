<?php

namespace App\Filament\Resources\Cafe\Pages;

use App\Filament\Resources\Cafe\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}
