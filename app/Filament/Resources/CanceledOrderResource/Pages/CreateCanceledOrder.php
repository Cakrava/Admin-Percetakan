<?php

namespace App\Filament\Resources\CanceledOrderResource\Pages;

use App\Filament\Resources\CanceledOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCanceledOrder extends CreateRecord
{
    protected static string $resource = CanceledOrderResource::class;
}
