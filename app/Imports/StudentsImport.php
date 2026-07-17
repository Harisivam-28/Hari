<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Student;
use App\Models\AcademicClass;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Find class by name
        $class = AcademicClass::where('name', $row['class'])->first();
        if (!$class) {
            return null;
        }

        // Create user account
        $user = User::create([
            'name' => $row['first_name'] . ' ' . $row['last_name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password'] ?? 'password123'),
            'role' => 'student',
        ]);

        // Generate admission number
        $admissionNo = 'SCH' . date('Y') . str_pad(Student::count() + 1, 3, '0', STR_PAD_LEFT);

        // Create student
        return new Student([
            'user_id' => $user->id,
            'admission_no' => $admissionNo,
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'date_of_birth' => $row['date_of_birth'] ?? null,
            'gender' => $row['gender'] ?? 'male',
            'blood_group' => $row['blood_group'] ?? null,
            'phone' => $row['phone'] ?? null,
            'address' => $row['address'] ?? null,
            'aadhar_number' => $row['aadhar_number'] ?? null,
            'parent_name' => $row['parent_name'],
            'parent_phone' => $row['parent_phone'] ?? null,
            'class_id' => $class->id,
            'section' => $row['section'] ?? null,
            'roll_number' => $row['roll_number'] ?? null,
            'admission_date' => $row['admission_date'] ?? now(),
            'status' => 'active',
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'parent_name' => 'required|string|max:255',
            'class' => 'required|string',
        ];
    }
}
