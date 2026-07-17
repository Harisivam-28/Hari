<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\FeeCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@school.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create default Academic Classes
        $classes = [
            'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5',
            'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10',
            'Class 11', 'Class 12',
        ];
        foreach ($classes as $class) {
            AcademicClass::create(['name' => $class]);
        }

        // Create default Academic Year
        AcademicYear::create([
            'name' => '2026-2027',
            'start_date' => '2026-04-01',
            'end_date' => '2027-03-31',
            'is_current' => true,
        ]);

        // Create default Fee Categories
        FeeCategory::create(['name' => 'Tuition Fee', 'description' => 'Regular tuition fee']);
        FeeCategory::create(['name' => 'Transport Fee', 'description' => 'School transport fee']);

        // Create a sample student user
        $studentUser = User::create([
            'name' => 'John Doe',
            'email' => 'student@school.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        $studentUser->student()->create([
            'admission_no' => 'SCH2026001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => '2010-05-15',
            'gender' => 'male',
            'blood_group' => 'O+',
            'phone' => '9876543210',
            'address' => '123 Main Street, New Delhi',
            'parent_name' => 'Mr. Robert Doe',
            'parent_phone' => '9876543211',
            'class_id' => 10, // Class 10
            'section' => 'A',
            'roll_number' => '15',
            'admission_date' => '2022-04-01',
            'status' => 'active',
        ]);
    }
}
