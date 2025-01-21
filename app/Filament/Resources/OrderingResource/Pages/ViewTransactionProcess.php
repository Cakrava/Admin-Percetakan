<?php

namespace App\Filament\Resources\OrderingResource\Pages;

use App\Filament\Resources\OrderingResource;
use App\Models\transaction;
use Filament\Resources\Pages\Page;

class ViewTransactionProcess extends Page
{
    protected static string $resource = OrderingResource::class;

    protected static string $view = 'filament.resources.ordering-resource.pages.view-transaction-process';
    public $record;

    // Mengambil record berdasarkan ID yang diteruskan melalui route
    public function mount($record): void
    {
        $this->record = transaction::find($record); // Ambil record berdasarkan ID
    }

    // Mengirim record ID ke Blade view
    public function getViewData(): array
    {
        return [
            'recordId' => $this->record->id, // Mengirim record ID ke Blade
        ];
    }
}
