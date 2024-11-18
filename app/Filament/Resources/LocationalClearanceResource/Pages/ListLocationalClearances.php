<?php

namespace App\Filament\Resources\LocationalClearanceResource\Pages;

use App\Filament\Resources\LocationalClearanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocationalClearances extends ListRecords
{
    protected static string $resource = LocationalClearanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
