<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailUserRelationManager extends RelationManager
{
    protected static string $relationship = 'detailUser';

    protected static ?string $recordTitleAttribute = 'nik';
    protected static ?string $label = 'detail pasien';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nik')
                    ->label('NIK')
                    ->unique(ignorable: fn ($record) => $record)
                    ->length(16)
                    ->hint('16 characters')
                    ->validationAttribute('NIK')
                    ->type('number')
                    ->required(),
                TextInput::make('ttl')
                    ->label('Tempat, tanggal lahir')
                    ->placeholder('Ex : Padang, 2 oktober 1992')
                    ->required(),
                TextInput::make('umur')
                    ->label('Umur (th)')
                    ->placeholder('Ex: 25')
                    ->type('number')
                    ->required(),
                Select::make('kelamin')
                    ->label('Jenis Kelamin')
                    ->placeholder('Pilih jenis kelamin')
                    ->options(['laki-laki' => 'Laki - Laki', 'perempuan' => 'Perempuan'])
                    ->required(),
                TextInput::make('phone')
                    ->label('No HP')
                    ->unique(ignorable: fn ($record) => $record)
                    ->placeholder('Ex: 0823123456')
                    ->type('number')
                    ->minLength(10)
                    ->maxLength(16)
                    ->required(),
                TextInput::make('berat')
                    ->label('Berat (Kg)')
                    ->placeholder('Ex: 55')
                    ->type('number')
                    ->required(),
                TextInput::make('tinggi')
                    ->label('Tinggi (cm)')
                    ->placeholder('Ex: 155')
                    ->type('number')
                    ->required(),
                TextInput::make('alergi')
                    ->label('Alergi')
                    ->hint('Kosongkan jika tidak ada'),
                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik'),
                Tables\Columns\TextColumn::make('ttl'),
                Tables\Columns\TextColumn::make('umur'),
                Tables\Columns\TextColumn::make('kelamin'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('berat'),
                Tables\Columns\TextColumn::make('tinggi'),
                Tables\Columns\TextColumn::make('alergi'),
                Tables\Columns\TextColumn::make('alamat'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

}
