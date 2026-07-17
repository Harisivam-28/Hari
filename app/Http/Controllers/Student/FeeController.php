<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\Payment;
use App\Models\AcademicYear;

class FeeController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $currentYear = AcademicYear::current();

        $feeDetails = [];

        if ($currentYear) {
            $feeStructures = FeeStructure::with('feeCategory')
                ->where('class_id', $student->class_id)
                ->where('academic_year_id', $currentYear->id)
                ->get();

            foreach ($feeStructures as $fee) {
                if ($fee->frequency === 'quarterly') {
                    $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
                    foreach ($quarters as $quarter) {
                        $payment = Payment::where('student_id', $student->id)
                            ->where('fee_structure_id', $fee->id)
                            ->where('quarter', $quarter)
                            ->where('status', 'completed')
                            ->first();

                        $feeDetails[] = [
                            'fee_structure' => $fee,
                            'category' => $fee->feeCategory->name,
                            'quarter' => $quarter,
                            'amount' => $fee->amount,
                            'due_date' => $fee->due_date,
                            'status' => $payment ? 'paid' : 'pending',
                            'payment' => $payment,
                        ];
                    }
                } else {
                    $payment = Payment::where('student_id', $student->id)
                        ->where('fee_structure_id', $fee->id)
                        ->where('quarter', 'ANNUAL')
                        ->where('status', 'completed')
                        ->first();

                    $feeDetails[] = [
                        'fee_structure' => $fee,
                        'category' => $fee->feeCategory->name,
                        'quarter' => 'ANNUAL',
                        'amount' => $fee->amount,
                        'due_date' => $fee->due_date,
                        'status' => $payment ? 'paid' : 'pending',
                        'payment' => $payment,
                    ];
                }
            }
        }

        return view('student.fees.index', compact('feeDetails', 'student'));
    }
}
