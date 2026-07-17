<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\FeeCategory;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    public function index()
    {
        $feeStructures = FeeStructure::with(['academicYear', 'academicClass', 'feeCategory'])
            ->latest()
            ->get();

        return view('admin.fees.structures.index', compact('feeStructures'));
    }

    public function create()
    {
        $classes = AcademicClass::all();
        $years = AcademicYear::latest()->get();
        $categories = FeeCategory::all();

        return view('admin.fees.structures.create', compact('classes', 'years', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:academic_classes,id',
            'fee_category_id' => 'required|exists:fee_categories,id',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:quarterly,annual',
            'due_date' => 'nullable|date',
        ]);

        FeeStructure::create($validated);

        return redirect()->route('admin.fee-structures.index')
            ->with('success', 'Fee structure added successfully!');
    }

    public function edit(FeeStructure $feeStructure)
    {
        $classes = AcademicClass::all();
        $years = AcademicYear::latest()->get();
        $categories = FeeCategory::all();

        return view('admin.fees.structures.edit', compact('feeStructure', 'classes', 'years', 'categories'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'class_id' => 'required|exists:academic_classes,id',
            'fee_category_id' => 'required|exists:fee_categories,id',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:quarterly,annual',
            'due_date' => 'nullable|date',
        ]);

        $feeStructure->update($validated);

        return redirect()->route('admin.fee-structures.index')
            ->with('success', 'Fee structure updated successfully!');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        if ($feeStructure->payments()->count() > 0) {
            return back()->with('error', 'Cannot delete fee structure with existing payments!');
        }

        $feeStructure->delete();

        return redirect()->route('admin.fee-structures.index')
            ->with('success', 'Fee structure deleted successfully!');
    }
}
