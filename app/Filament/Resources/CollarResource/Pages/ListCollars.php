<?php

namespace App\Filament\Resources\CollarResource\Pages;

use App\Filament\Resources\CollarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCollars extends ListRecords
{
    protected static string $resource = CollarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
