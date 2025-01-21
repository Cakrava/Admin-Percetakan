<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportDataResource\Pages;
use App\Filament\Resources\ReportDataResource\RelationManagers;
use App\Models\ReportData;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportDataResource extends Resource
{
      protected static ?string $navigationIcon = 'heroicon-o-newspaper';
      protected static ?string $modelLabel = 'Report';
      protected static ?string $navigationGroup = 'Aditional';
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
            'index' => Pages\ViewReportData::route('/'),
        ];
    }
}
