<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = AcademicClass::withCount('students')->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:academic_classes,name',
            'description' => 'nullable|string|max:500',
        ]);

        AcademicClass::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class added successfully!');
    }

    public function update(Request $request, AcademicClass $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:academic_classes,name,' . $class->id,
            'description' => 'nullable|string|max:500',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class updated successfully!');
    }

    public function destroy(AcademicClass $class)
    {
        if ($class->students()->count() > 0) {
            return back()->with('error', 'Cannot delete class with active students!');
        }

        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully!');
    }
}
