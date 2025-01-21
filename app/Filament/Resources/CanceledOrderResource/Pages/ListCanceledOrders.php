<?php

namespace App\Filament\Resources\CanceledOrderResource\Pages;

use App\Filament\Resources\CanceledOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCanceledOrders extends ListRecords
{
    protected static string $resource = CanceledOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
