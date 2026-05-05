<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            // Kelas 1A
            ['name' => 'Ahmad Fauzi',      'class' => '1A', 'gender' => 'L', 'nisn' => '0123456001'],
            ['name' => 'Siti Aisyah',      'class' => '1A', 'gender' => 'P', 'nisn' => '0123456002'],
            ['name' => 'Muhammad Rizki',   'class' => '1A', 'gender' => 'L', 'nisn' => '0123456003'],
            // Kelas 1B
            ['name' => 'Fatimah Zahra',    'class' => '1B', 'gender' => 'P', 'nisn' => '0123456004'],
            ['name' => 'Dimas Saputra',    'class' => '1B', 'gender' => 'L', 'nisn' => '0123456005'],
            // Kelas 2A
            ['name' => 'Nur Hidayah',      'class' => '2A', 'gender' => 'P', 'nisn' => '0123456006'],
            ['name' => 'Budi Santoso',     'class' => '2A', 'gender' => 'L', 'nisn' => '0123456007'],
            // Kelas 3A
            ['name' => 'Aulia Rahma',      'class' => '3A', 'gender' => 'P', 'nisn' => '0123456008'],
            ['name' => 'Rizal Maulana',    'class' => '3A', 'gender' => 'L', 'nisn' => '0123456009'],
            // Kelas 4A
            ['name' => 'Dewi Rahayu',      'class' => '4A', 'gender' => 'P', 'nisn' => '0123456010'],
            ['name' => 'Ilham Prasetyo',   'class' => '4A', 'gender' => 'L', 'nisn' => '0123456011'],
            // Kelas 5A
            ['name' => 'Nadia Kusuma',     'class' => '5A', 'gender' => 'P', 'nisn' => '0123456012'],
            ['name' => 'Fajar Nugroho',    'class' => '5A', 'gender' => 'L', 'nisn' => '0123456013'],
            // Kelas 6A
            ['name' => 'Rini Wulandari',   'class' => '6A', 'gender' => 'P', 'nisn' => '0123456014'],
            ['name' => 'Yoga Pratama',     'class' => '6A', 'gender' => 'L', 'nisn' => '0123456015'],
        ];

        foreach ($students as $data) {
            Student::firstOrCreate(
                ['nisn' => $data['nisn']],
                array_merge($data, [
                    'balance'   => fake()->randomElement([0, 5000, 10000, 15000, 20000, 25000]),
                    'is_active' => true,
                ])
            );
        }
    }
}