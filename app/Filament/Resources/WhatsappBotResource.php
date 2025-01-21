<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhatsappBotResource\Pages;
use App\Filament\Resources\WhatsappBotResource\RelationManagers;
use App\Models\WhatsappBot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WhatsappBotResource extends Resource
{


    protected static ?string $navigationGroup = 'Aditional';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListWhatsappBots::route('/'),
            'create' => Pages\CreateWhatsappBot::route('/create'),
            'edit' => Pages\EditWhatsappBot::route('/{record}/edit'),
        ];
    }
}
