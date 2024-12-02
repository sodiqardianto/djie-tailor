<?php

namespace App\Filament\Resources\OrderInResource\Pages;

use App\Filament\Resources\OrderInResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderIns extends ListRecords
{
    protected static string $resource = OrderInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
