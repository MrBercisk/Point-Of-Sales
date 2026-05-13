<?php

namespace App\Filament\Traits;

use App\Models\User;
use Filament\Facades\Filament;

trait HasFilamentPermission
{
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

    public static function canViewAny(): bool
    {
        $user = static::getUser();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->can(static::getPermissionPrefix() . '.view');
    }

    public static function canCreate(): bool
    {
        $user = static::getUser();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->can(static::getPermissionPrefix() . '.create');
    }

    public static function canEdit($record): bool
    {
        $user = static::getUser();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->can(static::getPermissionPrefix() . '.edit');
    }

    public static function canDelete($record): bool
    {
        $user = static::getUser();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->can(static::getPermissionPrefix() . '.delete');
    }

    public static function canDeleteAny(): bool
    {
        $user = static::getUser();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->can(static::getPermissionPrefix() . '.delete');
    }
}