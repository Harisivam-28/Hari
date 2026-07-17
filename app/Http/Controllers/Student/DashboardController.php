<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\FeeStructure;
use App\Models\AcademicYear;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $student->load('academicClass');
        $currentYear = AcademicYear::current();

        $totalFees = 0;
        $totalPaid = 0;
        $nextDueDate = null;

        if ($currentYear) {
            $feeStructures = FeeStructure::where('class_id', $student->class_id)
                ->where('academic_year_id', $currentYear->id)
                ->get();

            foreach ($feeStructures as $fee) {
                $totalFees += $fee->frequency === 'quarterly' ? $fee->amount * 4 : $fee->amount;

                $paid = Payment::where('student_id', $student->id)
                    ->where('fee_structure_id', $fee->id)
                    ->where('status', 'completed')
                    ->sum('amount');
                $totalPaid += $paid;

                if ($fee->due_date && (!$nextDueDate || $fee->due_date < $nextDueDate)) {
                    $nextDueDate = $fee->due_date;
                }
            }
        }

        $pendingAmount = max(0, $totalFees - $totalPaid);

        $recentPayments = Payment::with(['feeStructure.feeCategory'])
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->latest('payment_date')
            ->take(5)
            ->get();

        return view('student.dashboard', compact(
            'student',
            'totalFees',
            'totalPaid',
            'pendingAmount',
            'nextDueDate',
            'recentPayments'
        ));
    }
}
