<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\DetailUserRelationManager;
use App\Models\User;
use Faker\Core\Number;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Pasiens';
    protected static ?string $label = 'Pasien';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('RFID')
                    ->unique(ignorable: fn ($record) => $record)
                    ->required()
                    ->maxLength(255),
                // TextInput::make('nik')
                //     ->label('NIK')
                //     ->unique()
                //     ->length(16)
                //     ->hint('16 characters')
                //     ->validationAttribute('NIK')
                //     ->type('number')
                //     ->required(),
                // Select::make('roles')
                //     ->multiple()
                //     ->relationship('roles', 'name')
                //     ->preload()
                // TextInput::make('ttl')
                //     ->label('Tempat, tanggal lahir')
                //     ->placeholder('Ex : Padang, 2 oktober 1992')
                //     ->required(),
                // TextInput::make('umur')
                //     ->label('Umur (th)')
                //     ->placeholder('Ex: 25')
                //     ->type('number')
                //     ->required(),
                // Select::make('kelamin')
                //     ->label('Jenis Kelamin')
                //     ->placeholder('Pilih jenis kelamin')
                //     ->options(['laki-laki' => 'Laki - Laki', 'perempuan' => 'Perempuan'])
                //     ->required(),
                // TextInput::make('phone')
                //     ->label('No HP')
                //     ->unique()
                //     ->placeholder('Ex: 0823123456')
                //     ->type('number')
                //     ->minLength(10)
                //     ->maxLength(16)
                //     ->required(),
                // TextInput::make('berat')
                //     ->label('Berat (Kg)')
                //     ->placeholder('Ex: 55')
                //     ->type('number')
                //     ->required(),
                // TextInput::make('tinggi')
                //     ->label('Tinggi (cm)')
                //     ->placeholder('Ex: 155')
                //     ->type('number')
                //     ->required(),
                // TextInput::make('alergi')
                //     ->label('Alergi')
                //     ->hint('Kosongkan jika tidak ada'),
                // Textarea::make('alamat')
                //     ->label('Alamat')
                //     ->required(),
                // Forms\Components\TextInput::make('detail_id')
                // ->label('')
                // ->extraAttributes(['style' => 'display: none']),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('RFID')->searchable(),
                Tables\Columns\TextColumn::make('detailUser.nik')->label('NIK')->searchable(),
                Tables\Columns\TextColumn::make('detailUser.phone')->label('HP')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()->label('')->tooltip('View Pasien'),
                Tables\Actions\EditAction::make()->label('')->tooltip('Edit Pasien'),
                DeleteAction::make()->label('')->tooltip('Delete Pasien')
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_is', 'user');
    }

    
    public static function getRelations(): array
    {
        return [
            DetailUserRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }    
}
