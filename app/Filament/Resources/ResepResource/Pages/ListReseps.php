<?php

namespace App\Filament\Resources\ResepResource\Pages;

use App\Filament\Resources\ResepResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReseps extends ListRecords
{
    protected static string $resource = ResepResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
