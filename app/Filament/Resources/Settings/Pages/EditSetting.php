<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Models\Settings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clearCache')
                ->label('Clear Cache')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    Settings::clearCache();
                    Notification::make()
                        ->title('Cache berhasil dibersihkan!')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Settings berhasil disimpan!';
    }

    protected function afterSave(): void
    {
        Settings::clearCache();
    }
}