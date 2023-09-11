<?php

namespace App\Filament\Resources\RegisterResource\Pages;

use App\Filament\Resources\RegisterResource;
use App\Models\DetailDokter;
use App\Models\DetailUser;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegister extends EditRecord
{
    protected static string $resource = RegisterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // dd($data);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if($data['user_is'] == 'user'){
            $detail = new DetailUser();
            $detail->save();

            $data['detail_id'] = $detail['id'];


        }else{
            $detail = new DetailDokter();
            $detail->save();

            $data['detail_id'] = $detail['id'];
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Tipe user berhasil disimpan. silahkan buka menu sesuai tipe user untuk melengkapi data';
    }
}
