<?php

namespace App\Filament\Resources\ReportDataResource\Pages;

use App\Filament\Resources\ReportDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportData extends ListRecords
{
    protected static string $resource = ReportDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
