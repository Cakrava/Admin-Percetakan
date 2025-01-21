<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
protected static ?string $navigationGroup = 'Aditional';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\TextInput:: make('name')->required(),
            Forms\Components\TextInput:: make('username')->required(),
            Forms\Components\TextInput:: make('email')->required(),
            Forms\Components\TextInput:: make('address'),
            Forms\Components\TextInput:: make('number'),
            Forms\Components\TextInput:: make('password')->password(),
            Forms\Components\Select:: make('role')->options([
                'Admin' => 'Admin',
                'User' => 'User',
            ])->required(),
            Forms\Components\FileUpload:: make('image'),
            ])
            ;
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
              Tables\Columns\TextColumn::make('name')->searchable(),
              Tables\Columns\TextColumn::make('email')->searchable(),
              Tables\Columns\TextColumn::make(name: 'number'),
              Tables\Columns\TextColumn::make('role'),
              Tables\Columns\TextColumn::make('status')
    ->label('Status')
    ->formatStateUsing(function ($state) {
        return $state === 'verified' ? '✔ Verified' : $state;
    })
    ->color(function ($state) {
        return $state === 'verified' ? 'success' : 'danger';
    })
    ->extraAttributes(['class' => 'px-4 py-2 rounded text-white']),

              Tables\Columns\ImageColumn::make('image'),
            ])
            ->filters([
               
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggleRole')
    ->label(fn (User $record) => $record->role === 'user' ? 'Ubah ke Admin' : 'Ubah ke User') // Ubah label berdasarkan role
    ->action(function (User $record) {
        $record->role = $record->role === 'user' ? 'admin' : 'user'; // Toggle role
        $record->save();
    })
    ->color(fn (User $record) => $record->role === 'user' ? 'success' : 'warning') // Ubah warna berdasarkan role
    ->icon('heroicon-s-user')
    ->requiresConfirmation()
    ->tooltip(fn (User $record) => $record->role === 'user' ? 'Ubah role menjadi Admin' : 'Ubah role menjadi User'),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
