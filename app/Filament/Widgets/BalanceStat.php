<?php

namespace App\Filament\Widgets;

use App\Models\Balance;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BalanceStat extends BaseWidget
{
    protected int | string | array $columnSpan = 'full'; // Mengisi lebar layar penuh

    protected function getStats(): array
    {
        // Ambil total balance dari model Balance
        $totalBalance = Balance::sum('total_balance');

        // Ambil tanggal sekarang dan format seperti kalender
        $tanggalSekarang = now()->translatedFormat('d F Y'); // Format: 12 Oktober 2023

        // Hitung total pengeluaran bulan ini dari tabel transactions
        $totalPengeluaranBulanIni = Transaction::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('total_price');

        return [
            Stat::make('Total Balance', 'Rp ' . number_format($totalBalance, 0, ',', '.'))
                ->description('Total saldo yang tersedia')
                ->color('success') // Warna hijau
                ->icon('heroicon-o-currency-dollar'), // Ikon dollar

            Stat::make('Total Balance', 'Rp ' . number_format($totalPengeluaranBulanIni, 0, ',', '.'))
                ->description('Total pendapatan bulan ini')
                ->color('danger') // Warna merah
                ->icon('heroicon-o-arrow-trending-down'), // Ikon panah turun

            Stat::make('Tanggal Sekarang', $tanggalSekarang)
                ->description('Hari ini')
                ->color('primary') // Warna biru
                ->icon('heroicon-o-calendar'), // Ikon kalender
        ];
    }
}