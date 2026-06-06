<?php

namespace App\Filament\Resources\Cafe;

use App\Filament\Resources\Cafe\Pages\CreateProduct;
use App\Filament\Resources\Cafe\Pages\EditProduct;
use App\Filament\Resources\Cafe\Pages\ListProducts;
use App\Filament\Resources\Cafe\Schemas\ProductForm;
use App\Filament\Resources\Cafe\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Menu Kafe';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isCinemaAdmin() || auth()->user()?->isCafeAdmin() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
