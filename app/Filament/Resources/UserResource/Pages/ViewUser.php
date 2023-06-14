<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = User::find($data['id']);
        $data['detail_id'] = $user['detail_id'];
        $data['nik'] = $user->detailUser->nik;
        $data['ttl'] = $user->detailUser->ttl;
        $data['umur'] = $user->detailUser->umur;
        $data['kelamin'] = $user->detailUser->kelamin;
        $data['phone'] = $user->detailUser->phone;
        $data['berat'] = $user->detailUser->berat;
        $data['tinggi'] = $user->detailUser->tinggi;
        $data['alergi'] = $user->detailUser->alergi;
        $data['alamat'] = $user->detailUser->alamat;

        return $data;
    }
}
