<?php

namespace App\Filament\Resources\ResepResource\Pages;

use App\Filament\Resources\ResepResource;
use App\Models\History;
use App\Models\Resep;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResep extends EditRecord
{
    protected static string $resource = ResepResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['obat_id'] = json_decode($data['obat_id']);
        return $data;
    }

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     $data['obat_id'] = json_encode($data['obat_id']);
    //     $from = Carbon::createFromFormat('Y-m-d', $data['tgl_mulai']);
    //     $to = Carbon::createFromFormat('Y-m-d', $data['tgl_selesai'])->addDay();
    //     $difference = $from->diff($to)->days;
    //     $total = $difference / $data['perhari'] / $data['dosis'];
    //     // var_dump($data, $total);
    //     // if($data['perhari'] == 2){
    //         // }
    //     $dateList = [$from];
    //     $fromNew = Carbon::createFromFormat('Y-m-d', $data['tgl_mulai'])->subDays();
    //     $resep_id = Resep::orderBy('created_at', 'desc')->first();
    //     if(!$resep_id){
    //         $resep_id['id'] = 0;
    //     }
    //     $resep_id = $resep_id['id'] + 1;
    //     // dd($total);
    //     for($i = 1; $i < $total; $i++){
    //         $added = $fromNew->addDays($data['perhari']);
    //         array_push($dateList, $added);
    //         $from = $added;

    //         $history = new History();
    //         $history->resep_id = $resep_id;
    //         $history->hari_ke = $i;
    //         $history->tanggal = $dateList[$i - 1];
    //         $history->status = 'pending';
    //         $history->save();
    //     }


    //     return $data;
    // }
    
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Resep updated successfully!';
    }
}
