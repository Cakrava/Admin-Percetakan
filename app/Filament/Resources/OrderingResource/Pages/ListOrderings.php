<?php

namespace App\Filament\Resources\OrderingResource\Pages;

use App\Filament\Resources\OrderingResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Transaction;

class ListOrderings extends ListRecords
{
    protected static string $resource = OrderingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tambahkan action header jika diperlukan
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(Transaction::count()) // Total semua transaksi
                ->modifyQueryUsing(fn (Builder $query) => $query), // Tidak ada filter untuk "All"

            'process' => Tab::make('Process')
                ->badge(Transaction::where('status', 'Proses')->count()) // Total transaksi dengan status "Process"
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Proses')), // Filter untuk status "Process"

            'completed' => Tab::make('Completed')
                ->badge(Transaction::where('status', 'Completed')->count()) // Total transaksi dengan status "Completed"
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Completed')), // Filter untuk status "Completed"
        ];
    }
}
