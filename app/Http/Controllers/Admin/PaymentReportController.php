<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'feeStructure.feeCategory', 'feeStructure.academicClass']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        $payments = $query->latest('payment_date')->get();

        $totalCompleted = $payments->where('status', 'completed')->sum('amount');
        $totalPending = $payments->where('status', 'pending')->sum('amount');

        return view('admin.payments.index', compact('payments', 'totalCompleted', 'totalPending'));
    }
}
