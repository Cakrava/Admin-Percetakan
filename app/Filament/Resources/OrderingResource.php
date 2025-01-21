<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderingResource\Pages;
use App\Filament\Resources\OrderingResource\RelationManagers;
use App\Models\transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderingResource extends Resource
{
    protected static ?string $model = transaction::class;
   
    protected static ?string $modelLabel = 'Ordering Proses';
    protected static ?string $navigationGroup = 'Process';
    protected static ?int $navigationSort = 1; 
    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

   
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
            ->emptyStateHeading('Tidak ada data')
            ->emptyStateDescription('Silakan tambahkan data baru.')
            ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['Proses', 'Completed'])) // Tampilkan data dengan status "Proses" atau "Completed"

            ->columns([
                // Kolom ID Transaksi
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Transaksi')
                    ->searchable(),
    
                // Kolom Nama Customer
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Nama Customer')
                    ->searchable(),
    
                // Kolom Nomor Customer
                Tables\Columns\TextColumn::make('customer.number')
                    ->label('Nomor Customer')
                    ->searchable(),
    
                // Kolom Total Harga
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
    
                // Kolom Metode Pembayaran

    
                // Kolom Status Transaksi
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Canceled' => 'danger',
                        'Proses' => 'info',
                        'Completed' => 'success',
                        default => 'gray',
                    }),
    
                // Kolom Tanggal Transaksi
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Aksi View (Opsional)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListOrderings::route('/'),
            'view' => Pages\ViewTransactionProcess::route('/view/{record}'),
        ];
    }
}
