<?php

namespace App\Filament\Resources\ReportDataResource\Pages;

use App\Filament\Resources\ReportDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportData extends EditRecord
{
    protected static string $resource = ReportDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
