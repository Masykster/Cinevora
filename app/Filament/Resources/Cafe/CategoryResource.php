<?php

namespace App\Filament\Resources\Cafe;

use App\Filament\Resources\Cafe\Pages\CreateCategory;
use App\Filament\Resources\Cafe\Pages\EditCategory;
use App\Filament\Resources\Cafe\Pages\ListCategories;
use App\Filament\Resources\Cafe\Schemas\CategoryForm;
use App\Filament\Resources\Cafe\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Menu Kafe';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isCinemaAdmin() || auth()->user()?->isCafeAdmin() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
