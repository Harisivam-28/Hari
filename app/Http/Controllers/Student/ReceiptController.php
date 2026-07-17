<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $payments = Payment::with(['feeStructure.feeCategory', 'feeStructure.academicClass'])
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->latest('payment_date')
            ->get();

        return view('student.receipts.index', compact('payments', 'student'));
    }

    public function download(Payment $payment)
    {
        $student = auth()->user()->student;

        // Ensure student can only download their own receipts
        if ($payment->student_id !== $student->id) {
            abort(403);
        }

        $payment->load(['feeStructure.feeCategory', 'feeStructure.academicClass', 'student']);

        $pdf = Pdf::loadView('receipts.pdf', compact('payment', 'student'));

        return $pdf->download('receipt-' . $payment->receipt_number . '.pdf');
    }
}
