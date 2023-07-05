<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\DetailUser;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public $id_data;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('Reset')->tooltip('Reset Password')->action('openSettingsModal'),
        ];
    }

    public function openSettingsModal(): void
    {
        $user = User::find($this->id_data);
        if($user){
            $user->password = Hash::make('123456');
            $user->save();
        }

        Notification::make() 
            ->title('Password berhasil direset!')
            ->success()
            ->send(); 
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->id_data = $data['id'];
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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'User updated successfully!';
    }
}
