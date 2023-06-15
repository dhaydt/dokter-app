<?php

namespace App\Filament\Resources\DokterResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailDokterRelationManager extends RelationManager
{
    protected static string $relationship = 'detailDokter';

    protected static ?string $recordTitleAttribute = 'izin_praktek';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('izin_praktek')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('phone')
                    ->label('No. HP')
                    ->type('number')
                    ->unique(ignorable: fn ($record) => $record)
                    ->required(),
                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('izin_praktek'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('No. HP'),
                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat'),
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
