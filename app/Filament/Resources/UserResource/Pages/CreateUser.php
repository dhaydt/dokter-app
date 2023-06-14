<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\DetailUser;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        $data['password'] = Hash::make('123456');
        $detail = new DetailUser();
        $detail->save();
        $data['detail_id'] = $detail['id'];

        // $detail = DetailUser::find($data['detail_id']);
        // $detail->nik = $data['nik'];
        // $detail->ttl = $data['ttl'];
        // $detail->umur = $data['umur'];
        // $detail->kelamin = $data['kelamin'];
        // $detail->phone = $data['phone'];
        // $detail->berat = $data['berat'];
        // $detail->tinggi = $data['tinggi'];
        // $detail->alergi = $data['alergi'];
        // $detail->alamat = $data['alamat'];
        // $detail->save();

        return $data;
    }


    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Pasien registration successful!, please complete the pasien details in edit pasien to be able to use the account!';
    }
}
