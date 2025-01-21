<?php

namespace App\Filament\Resources\TransactionResource\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;

class BalanceBarChart extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan Per Bulan';

    public ?string $selectedYear = null;

    protected function getData(): array
    {
        $year = $this->selectedYear ?? date('Y');

        // Ambil data total pendapatan per bulan untuk tahun yang dipilih
        $transactions = Transaction::selectRaw('
            DATE_FORMAT(created_at, "%Y-%m") as month,
            SUM(total_price) as total_revenue
        ')
        ->whereYear('created_at', $year) // Filter berdasarkan tahun
        ->where('status', 'Completed') // Hanya transaksi yang selesai
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Buat array untuk 12 bulan dengan label singkat (Jan, Feb, dst.)
        $labels = [];
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = sprintf('%04d-%02d', $year, $i); // Format: YYYY-MM
            $labels[] = date('M', strtotime($month)); // Format: Jan, Feb, dst.
            $data[] = $transactions->firstWhere('month', $month)->total_revenue ?? 0; // Jika tidak ada data, gunakan 0
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Pendapatan',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)', // Biru transparan
                    'borderColor' => 'rgba(54, 162, 235, 1)', // Biru solid untuk border
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'animation' => [
                'duration' => 1000, // Durasi animasi dalam milidetik (1 detik)
                'easing' => 'easeOutQuart', // Jenis easing untuk animasi
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true, // Mulai sumbu Y dari 0
                ],
            ],
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('selectedYear')
                ->label('Pilih Tahun')
                ->options(function () {
                    // Ambil daftar tahun yang tersedia dari data transaksi
                    $years = Transaction::selectRaw('YEAR(created_at) as year')
                        ->where('status', 'Completed') // Hanya ambil tahun dari transaksi yang selesai
                        ->groupBy('year')
                        ->orderBy('year', 'desc')
                        ->pluck('year', 'year')
                        ->toArray();

                    // Tambahkan opsi tahun saat ini jika belum ada
                    $currentYear = date('Y');
                    if (!in_array($currentYear, $years)) {
                        $years[$currentYear] = $currentYear;
                    }

                    return $years;
                })
                ->default(date('Y')) // Default: tahun saat ini
                ->reactive() // Agar grafik diperbarui saat tahun dipilih
                ->searchable(), // Opsi pencarian
        ];
    }
}