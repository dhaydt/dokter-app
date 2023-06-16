<?php

namespace App\Filament\Resources\ResepResource\RelationManagers;

use App\Models\Obat;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class HistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'history';

    protected static ?string $recordTitleAttribute = 'resep_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('resep_id')
                    ->required()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('resep.obat_id')
                    ->label('Obat')
                    ->getStateUsing(function($record){
                        $obat_id = json_decode($record['resep']['obat_id']);
                        $obat = [];
                        foreach($obat_id as $o){
                            array_push($obat,Obat::find($o)['name']);
                        }

                        return $obat;
                    })->searchable(),
                    TextColumn::make('hari_ke')
                        ->label('Hari ke-'),
                    TextColumn::make('waktu_minum')
                        ->label('Waktu minum'),
                    TextColumn::make('img')->label('Foto'),
                    TextColumn::make('status'),
                    TextColumn::make('tanggal')->label('Batas waktu minum')->date('d M Y'),
                ])

            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
