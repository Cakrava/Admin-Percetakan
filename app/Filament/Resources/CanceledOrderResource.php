<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CanceledOrderResource\Pages;
use App\Filament\Resources\CanceledOrderResource\RelationManagers;
use App\Models\CanceledOrder;
use App\Models\transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CanceledOrderResource extends Resource
{
    protected static ?string $model = transaction::class;

    protected static ?string $modelLabel = 'Canceled Order';
    protected static ?string $navigationGroup = 'Process';
    protected static ?int $navigationSort = 2; 
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

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
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Canceled')) // Hanya tampilkan data dengan status "Pending"
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
                Tables\Columns\TextColumn::make('payment.method')
                    ->label('Metode Pembayaran')
                    ->searchable(),
    
                // Kolom Status Transaksi
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Canceled' => 'danger',
                        'Progres' => 'info',
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
                Tables\Actions\DeleteAction::make(), // Aksi Edit
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(), // Aksi Hapus Massal
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
            'index' => Pages\ListCanceledOrders::route('/'),
 
            'view' => Pages\ViewCanceledTransaction::route('/view/{record}'),
        ];
    }
}
