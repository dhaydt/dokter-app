<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokterResource\Pages;
use App\Filament\Resources\DokterResource\RelationManagers;
use App\Filament\Resources\DokterResource\RelationManagers\DetailDokterRelationManager;
use App\Models\Dokter;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use stdClass;

class DokterResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $label = 'Dokter';
    protected static ?string $navigationLabel = 'Dokter';
    protected static ?string $slug = 'Dokter';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')->label('RFID')
                    ->required(),
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
                Tables\Columns\TextColumn::make('code_uniq_formatted')->label('Kode Unik')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('RFID')->searchable(),
                Tables\Columns\TextColumn::make('detailDokter.izin_praktek')->label('Izin Praktek')->searchable(),
                Tables\Columns\TextColumn::make('detailDokter.phone')->label('HP')->searchable(),
                Tables\Columns\TextColumn::make('detailDokter.alamat')->label('Alamat')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('')->tooltip('Edit dokter'),
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Hapus dokter'),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_is', 'dokter')->orderBy('created_at', 'desc');
    }
    
    public static function getRelations(): array
    {
        return [
            DetailDokterRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokters::route('/'),
            'create' => Pages\CreateDokter::route('/create'),
            'edit' => Pages\EditDokter::route('/{record}/edit'),
        ];
    }    
}
