<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoryResource\RelationManagers\HistoryRelationManager;
use App\Filament\Resources\ResepResource\Pages;
use App\Filament\Resources\ResepResource\RelationManagers;
use App\Filament\Resources\ResepResource\RelationManagers\HistoryRelationManager as RelationManagersHistoryRelationManager;
use App\Models\Obat;
use App\Models\Resep;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class ResepResource extends Resource
{
    protected static ?string $model = Resep::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';
    protected static ?string $navigationLabel = 'Resep';

    public static function form(Form $form): Form
    {
        $obat = Obat::get();
        $newObat = [];
        foreach ($obat as $o) {
            $newObat[$o['id']] = $o['name'];
        }

        $user = User::where('user_is', 'user')->get();
        $newUser = [];
        foreach ($user as $u) {
            $newUser[$u['id']] = $u['name'] . ', ' . $u['email'];
        }

        $dokter = User::where('user_is', 'dokter')->get();
        $newDokter = [];
        foreach ($dokter as $d) {
            $newDokter[$d['id']] = $d['name'] . ', ' . $d['email'];
        }

        return $form
            ->schema(
                Card::make()->schema(
                    [
                        Select::make('obat_id')
                            ->label('Obat')
                            ->placeholder('Select Obat!')
                            ->columnSpan(2)
                            ->options($newObat)
                            ->required()
                            ->multiple(),
                        Select::make('user_id')
                            ->label('Patient')
                            ->placeholder('Select Patient!')
                            ->options($newUser)
                            ->required(),
                        Select::make('dokter_id')
                            ->label('Doctor')
                            ->placeholder('Select Doctor!')
                            ->options($newDokter)
                            ->required(),
                        Forms\Components\DatePicker::make('tgl_mulai')
                            ->required()
                            ->label('Tanggal mulai berobat'),
                        Forms\Components\DatePicker::make('tgl_selesai')
                            ->required()
                            ->label('Tanggal selesai berobat'),
                        Forms\Components\TextInput::make('dosis')
                            ->label('Kali minum obat')
                            ->hint('Berapa kali obat diminum')
                            ->type('number')
                            ->placeholder('Ex: 1')
                            ->required(),
                        Forms\Components\TextInput::make('perhari')
                            ->label('Jarak obat diminum')
                            ->hint('satuan: hari')
                            ->placeholder('Ex: 1'),
                    ]
                )->columns(2)
            );
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
                Tables\Columns\TextColumn::make('obat_id')->getStateUsing(function($record){
                    $obat_id = json_decode($record['obat_id']);
                    $obat = [];
                    foreach($obat_id as $o){
                        array_push($obat,Obat::find($o)['name']);
                    }

                    return $obat;
                })->searchable(),
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('dokter.name')->searchable(),
                Tables\Columns\TextColumn::make('tgl_mulai')
                    ->date(),
                Tables\Columns\TextColumn::make('tgl_selesai')
                    ->date(),
                Tables\Columns\TextColumn::make('dosis'),
                Tables\Columns\TextColumn::make('perhari'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagersHistoryRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReseps::route('/'),
            'create' => Pages\CreateResep::route('/create'),
            'edit' => Pages\EditResep::route('/{record}/edit'),
        ];
    }
}
