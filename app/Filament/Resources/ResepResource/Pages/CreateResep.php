<?php

namespace App\Filament\Resources\ResepResource\Pages;

use App\CPU\Helpers;
use App\Filament\Resources\ResepResource;
use App\Models\History;
use App\Models\Obat;
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

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     // $data['obat_id'] = json_encode($data['obat_id']);
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

    protected function afterCreate(): void
    {
        $resep = Resep::orderBy('created_at', 'desc')->first();

        $from = Carbon::createFromFormat('Y-m-d', $resep['tgl_mulai']);
        $to = Carbon::createFromFormat('Y-m-d', $resep['tgl_selesai'])->addDay();
        $difference = $from->diff($to)->days;
        $total = $difference / $resep['perhari'] / $resep['dosis'];
        // var_dump($data, $total);
        // if($data['perhari'] == 2){
            // }
        $dateList = [$from];
        $fromNew = Carbon::createFromFormat('Y-m-d', $resep['tgl_mulai'])->subDays();
        // $resep_id = Resep::orderBy('created_at', 'desc')->first();

        $resep_id = $resep['id'];
        // dd($total);
        for($i = 1; $i < $total; $i++){
            $added = $fromNew->addDays($resep['perhari']);
            array_push($dateList, $added);
            $from = $added;

            $history = new History();
            $history->resep_id = $resep_id;
            $history->hari_ke = $i;
            $history->tanggal = $dateList[$i - 1];
            $history->status = 'pending';
            $history->save();
        }

        $resep['code_uniq'] = Helpers::generateUniq('RB', $resep['id']);
        $code = [];

        foreach(json_decode($resep['obat_id']) as $d){
            $obt = Obat::find($d);
            if($obt){
                array_push($code, $obt['code_uniq']);
            }
        }

        $resep['code_uniq_obat'] = json_encode($code);
        $resep->save();
    }
}
