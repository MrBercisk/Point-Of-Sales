<?php

namespace App\Console\Commands;

use App\Services\PermissionDiscovery;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

/**
 * Sync permission yang terdefinisi di kode ke tabel permissions DB.
 *
 *   php artisan permissions:sync             → tambah permission baru
 *   php artisan permissions:sync --dry-run   → preview tanpa tulis ke DB
 *   php artisan permissions:sync --prune     → hapus permission orphan (ada di DB, tidak di kode)
 */
class SyncPermissionsCommand extends Command
{
    protected $signature = 'permissions:sync
                            {--dry-run : Preview saja, tidak tulis ke DB}
                            {--prune   : Hapus permission yang tidak ada di kode (hati-hati!)}';

    protected $description = 'Sync permission dari kode Filament ke database';

    public function handle(PermissionDiscovery $discovery): int
    {
        $isDryRun = $this->option('dry-run');
        $isPrune  = $this->option('prune');

        // ── 1. Discover dari kode ────────────────────────────────────
        $this->info('🔍 Scanning Filament Pages & Resources...');
        $discovered = $discovery->discover();
        $this->line("   Ditemukan <comment>{$discovered->count()}</comment> permission dari kode.");

        // ── 2. Bandingkan dengan DB ──────────────────────────────────
        $existing = Permission::pluck('name');
        $toCreate = $discovered->diff($existing);
        $orphans  = $existing->diff($discovered);

        // ── 3. Tampilkan tabel ringkasan ─────────────────────────────
        $this->newLine();

        $rows = collect()
            ->merge($toCreate->map(fn($p) => ['<fg=green>+ NEW</fg=green>',    $p]))
            ->merge($orphans->map(fn($p)  => ['<fg=red>- ORPHAN</fg=red>',     $p]))
            ->merge($existing->intersect($discovered)->map(fn($p) => ['<fg=gray>= EXISTS</fg=gray>', $p]));

        $this->table(['Status', 'Permission'], $rows->toArray());

        if ($isDryRun) {
            $this->warn('ℹ️  Dry-run mode aktif — tidak ada perubahan yang disimpan.');
            return self::SUCCESS;
        }

        // ── 4. Insert permission baru ────────────────────────────────
        if ($toCreate->isNotEmpty()) {
            foreach ($toCreate as $name) {
                Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            }
            $this->info("✅ {$toCreate->count()} permission baru ditambahkan.");
        } else {
            $this->line('✅ Tidak ada permission baru.');
        }

        // ── 5. Prune orphan (opsional) ───────────────────────────────
        if ($isPrune && $orphans->isNotEmpty()) {
            if ($this->confirm("Hapus {$orphans->count()} permission orphan dari DB?", false)) {
                Permission::whereIn('name', $orphans->toArray())->delete();
                $this->warn("🗑️  {$orphans->count()} permission orphan dihapus.");
            }
        } elseif ($orphans->isNotEmpty()) {
            $this->warn("⚠️  {$orphans->count()} permission orphan di DB. Jalankan dengan --prune untuk menghapus.");
        }

        // ── 6. Clear Spatie permission cache ─────────────────────────
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->info('🗑️  Permission cache di-clear.');

        $this->newLine();
        $this->info('🎉 Selesai!');

        return self::SUCCESS;
    }
}