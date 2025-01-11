<?php

namespace App\Filament\Resources\PlacketResource\Pages;

use App\Filament\Resources\PlacketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlackets extends ListRecords
{
    protected static string $resource = PlacketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
