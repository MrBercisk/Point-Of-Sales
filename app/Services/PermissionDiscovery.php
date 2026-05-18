<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class PermissionDiscovery
{
    protected array $scanPaths = [
        'Filament/Pages',
        'Filament/Resources',
    ];

    protected string $baseNamespace = 'App\\';

    public function discover(): Collection
    {
        return $this->collectClasses()
            ->flatMap(fn($class) => $this->extractPermissions($class))
            ->unique()
            ->sort()
            ->values();
    }

    public function discoverGrouped(): Collection
    {
        return $this->discover()
            ->groupBy(fn($perm) => explode('.', $perm)[0]);
    }

    // Internal

    protected function collectClasses(): Collection
    {
        $classes = collect();

        foreach ($this->scanPaths as $relativePath) {
            $dir = app_path($relativePath);
            if (! File::isDirectory($dir)) continue;

            foreach (File::allFiles($dir) as $file) {
                // Konversi path file → fully-qualified class name
                $relative  = str_replace([app_path() . DIRECTORY_SEPARATOR, '.php'], '', $file->getRealPath());
                $className = $this->baseNamespace . str_replace(DIRECTORY_SEPARATOR, '\\', $relative);

                if (! class_exists($className)) continue;

                $ref = new ReflectionClass($className);

                // Hanya class konkret yang menggunakan trait HasFilamentPermission
                if ($ref->isAbstract()) continue;
                if (! $this->usesTrait($ref, \App\Filament\Traits\HasFilamentPermission::class)) continue;

                $classes->push($className);
            }
        }

        return $classes;
    }

    protected function extractPermissions(string $class): array
    {
        try {
            return $class::getDefinedPermissions();
        } catch (\Throwable) {
            return [];
        }
    }

    protected function usesTrait(ReflectionClass $ref, string $trait): bool
    {
        do {
            if (in_array($trait, array_keys($ref->getTraits()), true)) {
                return true;
            }
        } while ($ref = $ref->getParentClass());

        return false;
    }
}