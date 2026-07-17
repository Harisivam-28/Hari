<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use Illuminate\Http\Request;

class FeeCategoryController extends Controller
{
    public function index()
    {
        $categories = FeeCategory::withCount('feeStructures')->get();
        return view('admin.fees.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fee_categories,name',
            'description' => 'nullable|string|max:500',
        ]);

        FeeCategory::create($validated);

        return redirect()->route('admin.fee-categories.index')
            ->with('success', 'Fee category added successfully!');
    }

    public function update(Request $request, FeeCategory $feeCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fee_categories,name,' . $feeCategory->id,
            'description' => 'nullable|string|max:500',
        ]);

        $feeCategory->update($validated);

        return redirect()->route('admin.fee-categories.index')
            ->with('success', 'Fee category updated successfully!');
    }

    public function destroy(FeeCategory $feeCategory)
    {
        if ($feeCategory->feeStructures()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing fee structures!');
        }

        $feeCategory->delete();

        return redirect()->route('admin.fee-categories.index')
            ->with('success', 'Fee category deleted successfully!');
    }
}
