<?php

namespace App\Filament\Resources\ModelTypeResource\Pages;

use App\Filament\Resources\ModelTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditModelType extends EditRecord
{
    protected static string $resource = ModelTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
