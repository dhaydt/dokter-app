<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoryResource\RelationManagers\HistoryRelationManager;
use App\Filament\Resources\ResepResource\Pages;
use App\Filament\Resources\ResepResource\RelationManagers;
use App\Filament\Resources\ResepResource\RelationManagers\HistoryRelationManager as RelationManagersHistoryRelationManager;
use App\Models\History;
use App\Models\Obat;
use App\Models\Resep;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
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
    protected static ?string $navigationGroup = 'Pasien';

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
                        TextInput::make('id')->label('ID Resep')->disabled()->hiddenOn('create'),
                        Select::make('user_id')
                            ->label('Pasien')
                            ->placeholder('Pilih pasien!')
                            ->options($newUser)
                            ->required(),
                        Select::make('dokter_id')
                            ->label('Dokter')
                            ->placeholder('Select Doctor!')
                            ->options($newDokter)
                            ->required(),
                        Forms\Components\DatePicker::make('tgl_mulai')
                            ->required()
                            ->label('Tanggal mulai berobat'),
                        Forms\Components\DatePicker::make('tgl_selesai')
                            ->required()
                            ->label('Tanggal selesai berobat'),
                        Card::make()
                            ->schema([
                                Repeater::make('resep_obat')
                                    ->label('Jenis Obat')
                                    ->relationship()
                                    ->schema([
                                        Select::make('obat_id')
                                            ->label('Nama Obat')
                                            ->placeholder('Pilih Obat!')
                                            ->options($newObat)
                                            ->required()
                                            ->reactive(),
                                        TextInput::make('tablet')
                                            ->type('number')
                                            ->hint('Jumlah tablet dalam sekali minum')
                                            ->default(1)
                                            ->required()
                                    ])->columns(2)->columnSpan('full'),
                            ])->columns(2),

                        Forms\Components\TextInput::make('dosis')
                            ->label('Kali minum obat')
                            ->hint('Berapa kali obat diminum')
                            ->type('number')
                            ->placeholder(1)
                            ->default(1)
                            ->required()
                            ->minValue(1)
                            ->maxValue(1),
                        Forms\Components\TextInput::make('perhari')
                            ->label('Jarak obat diminum')
                            ->required()
                            ->hint('satuan: hari')
                            ->placeholder('Ex: 1'),
                        Select::make('status_pengobatan')
                            ->placeholder('Pilih status pengobatan')
                            ->options(['fase intensif' => 'fase intensif', 'lanjutan' => 'Lanjutan'])
                            ->required(),
                        Textarea::make('note')
                            ->label('Catatan dokter')
                            ->required()
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
                        return (string) ($rowLoop->iteration +
                            ($livewire->tableRecordsPerPage * ($livewire->page - 1
                            ))
                        );
                    }
                ),
                Tables\Columns\TextColumn::make('code_uniq')->label('Kode Unik')->searchable(),
                // Tables\Columns\TextColumn::make('code_uniq_obat')->label("Kode Unik Obat")->getStateUsing(function ($record) {
                //      // $obat = [];
                //      $string = '';
                //      foreach (json_decode($record->code_uniq_obat) as $index => $o) {
                //          $string = $string . $o. ', ';
                //      }

                //     return $string;
                // })->searchable(),
                Tables\Columns\TextColumn::make('obat_id')->label("Obat")->getStateUsing(function ($record) {
                    // $obat = [];
                    $string = '';
                    foreach ($record->resep_obat as $index => $o) {
                        // array_push($obat, $o);
                        $string = $string . $o->obat->name . ' ('. $o->tablet .')' . ($index + 1 == $record->resep_obat->count() ? '': ', ');
                    }

                    return $string;
                })->searchable(),
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('dokter.name')->searchable(),
                Tables\Columns\TextColumn::make('tgl_mulai')
                    ->date(),
                Tables\Columns\TextColumn::make('tgl_selesai')
                    ->date(),
                Tables\Columns\TextColumn::make('dosis'),
                Tables\Columns\TextColumn::make('perhari'),
                Tables\Columns\TextColumn::make('note')->label('Catatan Dokter'),
                Tables\Columns\TextColumn::make('status_pengobatan')->label('Status Pengobatan'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make('record')
                    ->before(function(DeleteAction $action, $record){
                        $riwayat = History::where('resep_id', $record['id'])->get();
                        foreach($riwayat as $r){
                            $r->delete();
                        }
                    }),
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
