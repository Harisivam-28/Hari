<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Payment;
use App\Models\AcademicYear;
use App\Models\FeeStructure;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::where('status', 'active')->count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $pendingFees = $this->calculatePendingFees();
        $recentPayments = Payment::with(['student', 'feeStructure.feeCategory'])
            ->where('status', 'completed')
            ->latest('payment_date')
            ->take(10)
            ->get();

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'amount' => Payment::where('status', 'completed')
                    ->whereYear('payment_date', $date->year)
                    ->whereMonth('payment_date', $date->month)
                    ->sum('amount'),
            ];
        }

        // Payment status breakdown for pie chart
        $paymentStats = [
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalRevenue',
            'pendingFees',
            'recentPayments',
            'monthlyRevenue',
            'paymentStats'
        ));
    }

    private function calculatePendingFees(): float
    {
        $currentYear = AcademicYear::current();
        if (!$currentYear) return 0;

        $totalFees = 0;
        $students = Student::with('academicClass')->where('status', 'active')->get();

        foreach ($students as $student) {
            $feeStructures = FeeStructure::where('class_id', $student->class_id)
                ->where('academic_year_id', $currentYear->id)
                ->get();

            foreach ($feeStructures as $fee) {
                $totalDue = $fee->frequency === 'quarterly' ? $fee->amount * 4 : $fee->amount;
                $totalPaid = Payment::where('student_id', $student->id)
                    ->where('fee_structure_id', $fee->id)
                    ->where('status', 'completed')
                    ->sum('amount');
                $totalFees += max(0, $totalDue - $totalPaid);
            }
        }

        return $totalFees;
    }
}
