<?php

namespace App\Filament\Resources\OrderingResource\Pages;

use App\Filament\Resources\OrderingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrdering extends CreateRecord
{
    protected static string $resource = OrderingResource::class;
}
