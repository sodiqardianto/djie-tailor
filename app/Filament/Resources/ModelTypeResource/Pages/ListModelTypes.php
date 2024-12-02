<?php

namespace App\Filament\Resources\ModelTypeResource\Pages;

use App\Filament\Resources\ModelTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModelTypes extends ListRecords
{
    protected static string $resource = ModelTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
