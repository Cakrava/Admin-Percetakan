<?php

namespace App\Filament\Resources\ServiceResource\Widgets;

use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServiceCard extends BaseWidget
{
    protected function getStats(): array
    {
        // Menghitung jumlah total layanan
        $totalServices = Service::count();

        // Menghitung jumlah layanan yang bisa di-customize
        $customizableServices = Service::where('isCustomize', 'Yes')->count();

        // Menghitung rata-rata harga layanan
        $averagePrice = Service::avg('price');

        return [
            Stat::make('Total Layanan', $totalServices)
                ->description('Jumlah total layanan yang tersedia')
                ->color('primary'),

            Stat::make('Layanan Customizable', $customizableServices)
                ->description('Jumlah layanan yang bisa di-customize')
                ->color('success'),

            Stat::make('Rata-rata Harga', number_format($averagePrice, 2))
                ->description('Rata-rata harga layanan')
                ->color('warning'),
        ];
    }
}