<?php

namespace App\Filament\Resources\OrderProcessResource\Pages;

use App\Filament\Resources\OrderProcessResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderProcesses extends ListRecords
{
    protected static string $resource = OrderProcessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
