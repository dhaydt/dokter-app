<?php

namespace App\Filament\Resources\ResepResource\Pages;

use App\Filament\Resources\ResepResource;
use App\Models\History;
use App\Models\Resep;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateResep extends CreateRecord
{
    protected static string $resource = ResepResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Resep created successfully!';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['obat_id'] = json_encode($data['obat_id']);
        $from = Carbon::createFromFormat('Y-m-d', $data['tgl_mulai']);
        $to = Carbon::createFromFormat('Y-m-d', $data['tgl_selesai']);
        $difference = $from->diff($to)->days;
        $total = $difference / $data['perhari'] / $data['dosis'];
        if($data['perhari'] == 2){
            $from = Carbon::createFromFormat('Y-m-d', $data['tgl_mulai'])->addDay();
        }
        $dateList = [$from];
        $resep_id = Resep::orderBy('created_at', 'desc')->first();
        $resep_id = $resep_id['id'] + 1;
        for($i = 1; $i < $total; $i++){
            $added = $from->addDays($data['perhari']);
            array_push($dateList, $added);
            $from = $added;

            $history = new History();
            $history->resep_id = $resep_id;
            $history->hari_ke = $i;
            $history->tanggal = $dateList[$i - 1];
            $history->status = 'pending';
            $history->save();

        }


        return $data;
    }
}
