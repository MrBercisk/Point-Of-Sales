<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Models\Settings;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(): void
    {
        parent::mount();

        // Setting hanya 1 row — langsung redirect ke edit
        $setting = Settings::first();
        if ($setting) {
            redirect(SettingResource::getUrl('edit', ['record' => $setting->id]));
        }
    }
}