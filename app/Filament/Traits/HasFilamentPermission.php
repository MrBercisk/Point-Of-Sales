<?php

namespace App\Filament\Traits;

use App\Models\User;
use Filament\Facades\Filament;

trait HasFilamentPermission
{
    // Definisikan tiap page:
    // protected static string $permissionPrefix = 'kitchen-display';
    // buat Resource: otomatis dari nama model jika tidak didefinisikan.

    protected static function getPermissionPrefix(): string
    {
        return static::$permissionPrefix
            ?? strtolower(class_basename(static::$model ?? ''));
    }

    protected static function getUser(): ?User
    {
        /** @var User|null $user */
        $user = Filament::auth()->user();
        return $user;
    }

    // Permission Discovery
    // Aksi default untuk Resource
    // Untuk Page, SyncPermissionsCommand hanya view 
    protected static array $permissionActions = [
        'view', 'create', 'edit', 'delete',
    ];

    public static function getDefinedPermissions(): array
    {
        $prefix = static::getPermissionPrefix();
        if (! $prefix) return [];

        $isPage  = is_subclass_of(static::class, \Filament\Pages\Page::class);
        $actions = $isPage ? ['view'] : static::$permissionActions;

        return array_map(fn($action) => "{$prefix}.{$action}", $actions);
    }


    public static function canViewAny(): bool
    {
        $user = static::getUser();
        if (! $user) return false;
        if ($user->hasRole('super_admin')) return true;
        return $user->can(static::getPermissionPrefix() . '.view');
    }

    public static function canCreate(): bool
    {
        $user = static::getUser();
        if (! $user) return false;
        if ($user->hasRole('super_admin')) return true;
        return $user->can(static::getPermissionPrefix() . '.create');
    }

    public static function canEdit($record): bool
    {
        $user = static::getUser();
        if (! $user) return false;
        if ($user->hasRole('super_admin')) return true;
        return $user->can(static::getPermissionPrefix() . '.edit');
    }

    public static function canDelete($record): bool
    {
        $user = static::getUser();
        if (! $user) return false;
        if ($user->hasRole('super_admin')) return true;
        return $user->can(static::getPermissionPrefix() . '.delete');
    }

    public static function canDeleteAny(): bool
    {
        $user = static::getUser();
        if (! $user) return false;
        if ($user->hasRole('super_admin')) return true;
        return $user->can(static::getPermissionPrefix() . '.delete');
    }

    public static function canAccess(): bool
    {
        $user = static::getUser();
        if (! $user) return false;
        if ($user->hasRole('super_admin')) return true;
        return $user->can(static::getPermissionPrefix() . '.view');
    }
}