<?php

namespace App\Filament\Resources\DokterResource\Pages;

use App\Filament\Resources\DokterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDokters extends ListRecords
{
    protected static string $resource = DokterResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
