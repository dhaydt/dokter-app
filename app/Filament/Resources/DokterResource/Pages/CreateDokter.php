<?php

namespace App\Filament\Resources\DokterResource\Pages;

use App\CPU\Helpers;
use App\Filament\Resources\DokterResource;
use App\Models\DetailDokter;
use App\Models\User;
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

    protected function afterCreate(): void
    {
        $dokter = User::where('user_is', 'dokter')->orderBy('created_at', 'desc')->first();

        $dokter['code_uniq'] = Helpers::generateUniq('UD', $dokter['id']);
        $dokter->save();
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Doctor registration successful!, please complete the doctor details in edit doctor to be able to use the account!';
    }
}
