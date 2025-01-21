<?php

namespace App\Filament\Resources\OrderingResource\Pages;

use App\Filament\Resources\OrderingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrdering extends EditRecord
{
    protected static string $resource = OrderingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
