<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction; // Pastikan model Transaction di-import
use Filament\Resources\Pages\Page;

class ViewTransaction extends Page
{
    protected static string $resource = TransactionResource::class;
    protected static string $view = 'filament.resources.transaction-resource.pages.view-transaction';

    // Properti untuk menyimpan record
    public $record;

    // Mengambil record berdasarkan ID yang diteruskan melalui route
    public function mount($record): void
    {
        $this->record = Transaction::find($record); // Ambil record berdasarkan ID
    }

    // Mengirim record ID ke Blade view
    public function getViewData(): array
    {
        return [
            'recordId' => $this->record->id, // Mengirim record ID ke Blade
        ];
    }
}