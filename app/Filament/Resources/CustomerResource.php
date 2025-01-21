<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Master';
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            // Group 1: Name dan Address (Kolom 1)
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label('Nama Lengkap'), // Label lebih deskriptif
    
                    Forms\Components\TextInput::make('address')
                        ->required()
                        ->label('Alamat'),
                ])
                ->columnSpan(1), // Menempati 1 kolom
    
            // Group 2: Number dan Email (Kolom 2 dan 3)
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\TextInput::make('number')
                        ->required()
                        ->label('Nomor Telepon'),
    
                    Forms\Components\TextInput::make('email')
                        ->required()
                        ->label('Alamat Email')
                        ->email(), // Validasi email otomatis
                ])
                ->columnSpan(2), // Menempati 2 kolom
        ])
        ->columns(3); 
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('name')->label('Nama Lengkap'),
            Tables\Columns\TextColumn::make('address')->label('Alamat'),
            Tables\Columns\TextColumn::make('number')->label('Nomor Telepon'),
            Tables\Columns\TextColumn::make('email')->label('Alamat Email'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
        ];
    }
}
