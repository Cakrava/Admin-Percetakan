<?php

namespace App\Filament\Widgets;

use App\Models\BalanceHistory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TabelRiwayatKeuangan extends BaseWidget
{
protected int | string | array $columnSpan = 'half'; // Agar tabel memenuhi lebar halaman

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BalanceHistory::query()->latest()->limit(5) // Ambil 5 data terbaru
            )
            ->emptyStateHeading('Tidak ada data')
            ->emptyStateDescription('Belum ada riwayat dana masuks')
            ->columns([

                Tables\Columns\TextColumn::make('balance')
                ->label('Pemasukan')
                ->numeric()
                ->color('success')
                ->formatStateUsing(function ($state) {
                    return '+ Rp ' . number_format($state, 0, ',', '.'); // Tambahkan tanda + di depan Rp
                }), // Format sebagai mata uang IDR (Rupiah)


                Tables\Columns\TextColumn::make('message_history')
                    ->label('Pesan Riwayat'),

              
                Tables\Columns\TextColumn::make('tanggal') // Menggunakan accessor "tanggal"
                    ->label('Tanggal'),

                // Tables\Columns\TextColumn::make('waktu') // Menggunakan accessor "waktu"
                //     ->label('Waktu')
                //     ->color('primary'),
            ])
            ->defaultSort('created_at', 'desc') // Urutkan berdasarkan tanggal terbaru
            ->paginated(false); // Nonaktifkan pagination
    }
}