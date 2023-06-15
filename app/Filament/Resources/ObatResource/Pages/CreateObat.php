<?php

namespace App\Filament\Resources\ObatResource\Pages;

use App\Filament\Resources\ObatResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateObat extends CreateRecord
{
    protected static string $resource = ObatResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Obat created successfully!';
    }

}
