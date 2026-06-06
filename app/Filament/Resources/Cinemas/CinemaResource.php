<?php

namespace App\Filament\Resources\Cinemas;

use App\Filament\Resources\Cinemas\Pages\CreateCinema;
use App\Filament\Resources\Cinemas\Pages\EditCinema;
use App\Filament\Resources\Cinemas\Pages\ListCinemas;
use App\Filament\Resources\Cinemas\Schemas\CinemaForm;
use App\Filament\Resources\Cinemas\Tables\CinemasTable;
use App\Models\Cinema;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CinemaResource extends Resource
{
    protected static ?string $model = Cinema::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isCinemaAdmin() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return CinemaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CinemasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StudiosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCinemas::route('/'),
            'create' => CreateCinema::route('/create'),
            'edit' => EditCinema::route('/{record}/edit'),
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
