<?php
namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Permission;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;
    protected array $permissionsToSync = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pisahkan permissions_ dari data utama
        $this->permissionsToSync = [];

        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'permissions_')) {
                $this->permissionsToSync = array_merge(
                    $this->permissionsToSync,
                    $value ?? []
                );
                unset($data[$key]);
            }
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if (!empty($this->permissionsToSync)) {
            $this->record->syncPermissions($this->permissionsToSync);
        }
    }
}