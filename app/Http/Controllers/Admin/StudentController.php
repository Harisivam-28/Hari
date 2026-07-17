<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\AcademicClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'academicClass'])->latest()->get();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $classes = AcademicClass::all();
        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'aadhar_number' => 'nullable|string|max:12',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'nullable|string|max:15',
            'class_id' => 'required|exists:academic_classes,id',
            'section' => 'nullable|string|max:5',
            'roll_number' => 'nullable|string|max:10',
            'admission_date' => 'nullable|date',
        ]);

        // Create user account
        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('students/photos', 'public');
        }

        // Generate admission number
        $admissionNo = 'SCH' . date('Y') . str_pad(Student::count() + 1, 3, '0', STR_PAD_LEFT);

        // Create student profile
        $user->student()->create([
            'admission_no' => $admissionNo,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'blood_group' => $validated['blood_group'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'photo' => $photoPath,
            'aadhar_number' => $validated['aadhar_number'],
            'parent_name' => $validated['parent_name'],
            'parent_phone' => $validated['parent_phone'],
            'class_id' => $validated['class_id'],
            'section' => $validated['section'],
            'roll_number' => $validated['roll_number'],
            'admission_date' => $validated['admission_date'] ?? now(),
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student added successfully! Admission No: ' . $admissionNo);
    }

    public function show(Student $student)
    {
        $student->load(['user', 'academicClass', 'payments.feeStructure.feeCategory']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = AcademicClass::all();
        $student->load('user');
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'aadhar_number' => 'nullable|string|max:12',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'nullable|string|max:15',
            'class_id' => 'required|exists:academic_classes,id',
            'section' => 'nullable|string|max:5',
            'roll_number' => 'nullable|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        // Update user
        $student->user->update([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
        ]);

        // Handle photo
        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = $request->file('photo')->store('students/photos', 'public');
        }

        $student->update($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully!');
    }

    public function destroy(Student $student)
    {
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }
        $student->user->delete(); // Cascades to student

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully!');
    }

    /**
     * Show the import form.
     */
    public function importForm()
    {
        return view('admin.students.import');
    }

    /**
     * Handle CSV import.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:5120',
        ]);

        try {
            Excel::import(new StudentsImport, $request->file('file'));

            return redirect()->route('admin.students.index')
                ->with('success', 'Students imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
