<?php

namespace App\Filament\Pages;

use App\Filament\Traits\HasFilamentPermission;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFilamentPermission;

    protected static string $permissionPrefix = 'dashboard';
}