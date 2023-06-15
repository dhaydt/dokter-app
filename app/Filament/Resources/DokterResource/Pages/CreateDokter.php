<?php

namespace App\Filament\Resources\DokterResource\Pages;

use App\Filament\Resources\DokterResource;
use App\Models\DetailDokter;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateDokter extends CreateRecord
{
    protected static string $resource = DokterResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make('123456');
        $data['user_is'] = 'dokter';
        $detail = new DetailDokter();
        $detail->save();
        $data['detail_id'] = $detail['id'];


        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Doctor registration successful!, please complete the doctor details in edit doctor to be able to use the account!';
    }
}
