<?php

namespace App\Filament\Resources\CanceledOrderResource\Pages;

use App\Filament\Resources\CanceledOrderResource;
use App\Models\transaction;
use Filament\Resources\Pages\Page;

class ViewCanceledTransaction extends Page
{
    protected static string $resource = CanceledOrderResource::class;

    protected static string $view = 'filament.resources.canceled-order-resource.pages.view-canceled-transaction';
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
