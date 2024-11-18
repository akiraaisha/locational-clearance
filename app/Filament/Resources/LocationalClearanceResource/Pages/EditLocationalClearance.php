<?php

namespace App\Filament\Resources\LocationalClearanceResource\Pages;

use App\Filament\Resources\LocationalClearanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocationalClearance extends EditRecord
{
    protected static string $resource = LocationalClearanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
