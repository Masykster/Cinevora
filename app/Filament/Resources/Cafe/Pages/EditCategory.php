<?php

namespace App\Filament\Resources\Cafe\Pages;

use App\Filament\Resources\Cafe\CategoryResource;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;
}
