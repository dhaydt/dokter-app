<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoryResource\Pages;
use App\Filament\Resources\HistoryResource\RelationManagers;
use App\Models\History;
use App\Models\Obat;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class HistoryResource extends Resource
{
    protected static ?string $model = History::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-list';
    protected static ?string $navigationGroup = 'Pasien';
    protected static ?string $label = 'Riwayat';
    protected static ?string $navigationLabel = 'Riwayat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->getStateUsing(
                    static function (stdClass $rowLoop, HasTable $livewire): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->tableRecordsPerPage * (
                                $livewire->page - 1
                            ))
                        );
                    }
                ),
                Tables\Columns\TextColumn::make('resep.obat_id')->getStateUsing(function($record){
                    if($record['resep']){
                        // dd($record['resep']['resep_obat']);
                        $obat_id = $record['resep']['resep_obat'];
                        $obat = [];
                        foreach($obat_id as $o){
                            if($o['obat']){
                                array_push($obat,$o['obat']['name']);
                            }else{
                                array_push($obat, 'data obat dihapus');
                            }
                        }
    
                        return $obat;
                    }
                    return 'Invalid resep';
                }),
                Tables\Columns\TextColumn::make('resep.user.name')->label('Patient')->searchable(),
                Tables\Columns\TextColumn::make('hari_ke')->label('Hari Ke'),
                Tables\Columns\TextColumn::make('waktu_minum')->label('Waktu diminum')->date('d M Y, H:i'),
                Tables\Columns\TextColumn::make('img')->label('Foto'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('tanggal')->label('Batas minum obat')->date('d M Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHistories::route('/'),
            'create' => Pages\CreateHistory::route('/create'),
            'edit' => Pages\EditHistory::route('/{record}/edit'),
        ];
    }    
}
