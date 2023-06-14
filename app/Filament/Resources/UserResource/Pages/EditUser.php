<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\DetailUser;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $detail = DetailUser::find($data['detail_id']);
        $detail->nik = $data['nik'];
        $detail->ttl = $data['ttl'];
        $detail->umur = $data['umur'];
        $detail->kelamin = $data['kelamin'];
        $detail->phone = $data['phone'];
        $detail->berat = $data['berat'];
        $detail->tinggi = $data['tinggi'];
        $detail->alergi = $data['alergi'];
        $detail->alamat = $data['alamat'];
        $detail->save();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'User updated successfully!';
    }
}
