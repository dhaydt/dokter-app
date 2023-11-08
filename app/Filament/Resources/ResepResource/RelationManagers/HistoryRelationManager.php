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
                        return (string) ($rowLoop->iteration +
                            ($livewire->tableRecordsPerPage * ($livewire->page - 1
                            ))
                        );
                    }
                ),
                Tables\Columns\TextColumn::make('resep.obat_id')->getStateUsing(function ($record) {
                    if ($record['resep']) {
                        // dd($record['resep']['resep_obat']);
                        $obat_id = $record['resep']['resep_obat'];
                        $obat = [];
                        foreach ($obat_id as $o) {
                            if ($o['obat']) {
                                array_push($obat, $o['obat']['name']);
                            } else {
                                array_push($obat, 'data obat dihapus');
                            }
                        }

                        return $obat;
                    }
                    return 'Invalid resep';
                })->searchable(),
                TextColumn::make('code_uniq_resep')
                    ->label('Kode Unik Resep'),
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
