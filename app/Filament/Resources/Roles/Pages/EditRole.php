<?php
namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Permission;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;
    protected array $permissionsToSync = [];

    // form di-load, pecah permissions ke field masing-masing group
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $allPermissions = $this->record->permissions->pluck('name')->toArray();

        $grouped = Permission::all()->groupBy(
            fn($p) => explode('.', $p->name)[0]
        );

        foreach ($grouped as $group => $perms) {
            $groupNames = $perms->pluck('name')->toArray();
            $data["permissions_{$group}"] = array_values(
                array_intersect($allPermissions, $groupNames)
            );
        }

        return $data;
    }

    // Saat save gabungkan kembali semua permissions_* lalu sync
    protected function mutateFormDataBeforeSave(array $data): array
    {
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

    protected function afterSave(): void
    {
        $this->record->syncPermissions($this->permissionsToSync ?? []);
    }
}