<?php

namespace App\Filament\Resources\ReportDataResource\Pages;

use App\Filament\Resources\ReportDataResource;
use Filament\Resources\Pages\Page;

class ViewReportData extends Page
{
    protected static string $resource = ReportDataResource::class;

    protected static string $view = 'filament.resources.report-data-resource.pages.view-report-data';
}
