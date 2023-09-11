<?php

namespace App\Filament\Resources\RegisterResource\Pages;

use App\Filament\Resources\RegisterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRegister extends CreateRecord
{
    protected static string $resource = RegisterResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Tipe user berhasil disimpan. jika tipe user adalah pasien, silahakn buka menu pasien untuk melengkapi data. Jika dokter, silahkan buka menu dokter untuk melengkapi data';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
