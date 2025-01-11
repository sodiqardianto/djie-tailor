<?php

namespace App\Filament\Resources\CollarResource\Pages;

use App\Filament\Resources\CollarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCollar extends EditRecord
{
    protected static string $resource = CollarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
