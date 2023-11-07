<?php

namespace App\Filament\Resources\ObatResource\Pages;

use App\CPU\Helpers;
use App\Filament\Resources\ObatResource;
use App\Models\Obat;
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
    protected function afterCreate(): void
    {
        $obat = Obat::orderBy('created_at', 'desc')->first();

        $obat['code_uniq'] = Helpers::generateUniq('OB', $obat['id']);
        $obat->save();
    }

}
