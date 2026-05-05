<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\StudentResource\Pages;
use App\Filament\Resources\Students\Pages\CreateStudent;
use App\Filament\Resources\Students\Pages\EditStudent;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Filament\Resources\Students\Pages\ViewStudent;
use App\Filament\Resources\Students\Schemas\StudentForm;
use App\Filament\Resources\Students\Schemas\StudentTable;

use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;
    protected static string|UnitEnum|null $navigationGroup = 'Siswa';
    protected static ?string $navigationLabel = 'Data Siswa';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $modelLabel      = 'Siswa';
    protected static ?string $pluralModelLabel = 'Data Siswa';

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'edit'   => EditStudent::route('/{record}/edit'),
            'view'   => ViewStudent::route('/{record}'),
        ];
    }
}