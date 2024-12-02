<?php

namespace App\Filament\Resources\OrderInResource\Pages;

use App\Filament\Resources\OrderInResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderIn extends EditRecord
{
    protected static string $resource = OrderInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
