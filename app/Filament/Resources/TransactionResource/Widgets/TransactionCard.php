<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionCard extends BaseWidget
{
    protected function getStats(): array
    {
        // Menghitung jumlah total transaksi
        $totalTransactions = Transaction::count();

        // Menghitung total pendapatan dari transaksi yang sudah selesai
        $totalRevenue = Transaction::where('status', 'Completed')->sum('total_price');

        // Menghitung jumlah transaksi yang masih "Pending"
        $pendingTransactions = Transaction::where('status', 'Pending')->count();
        $processTransactions = Transaction::where('status', 'Proses')->count();

        // Menghitung jumlah transaksi yang sudah "Completed"
        $completedTransactions = Transaction::where('status', 'Completed')->count();

        return [
            Stat::make('Total Transaksi', $totalTransactions)
                ->description('Jumlah total transaksi')
                ->color('primary'),

           
            Stat::make('Transaksi Pending', $pendingTransactions)
                ->description('Jumlah transaksi yang masih pending')
                ->color('warning'),

                Stat::make('Transaksi Proses', $processTransactions)
                ->description('Jumlah transaksi yang masih dalam proses')
                ->color('success'),

            Stat::make('Transaksi Selesai', $completedTransactions)
                ->description('Jumlah transaksi yang sudah selesai')
                ->color('success'),
        ];
    }
}