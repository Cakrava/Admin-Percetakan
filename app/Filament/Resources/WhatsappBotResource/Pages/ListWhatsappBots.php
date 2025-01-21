<?php

namespace App\Filament\Resources\WhatsappBotResource\Pages;

use App\Filament\Resources\WhatsappBotResource;
use Filament\Resources\Pages\Page;


class ListWhatsappBots extends Page
{
    protected static string $resource = WhatsappBotResource::class;

    // Lokasi view custom
    protected static string $view = 'filament.resources.whatsapp-bot.whatsapp';

    public function mount(): void
    {
        // Logika tambahan jika diperlukan
    }
}
