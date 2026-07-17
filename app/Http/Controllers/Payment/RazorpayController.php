<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\FeeStructure;
use App\Models\Payment;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class RazorpayController extends Controller
{
    private Api $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('razorpay.key_id'),
            config('razorpay.key_secret')
        );
    }

    /**
     * Create a Razorpay order for the selected fees.
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'fee_items' => 'required|array|min:1',
            'fee_items.*.fee_structure_id' => 'required|exists:fee_structures,id',
            'fee_items.*.quarter' => 'required|string',
        ]);

        $student = auth()->user()->student;
        $totalAmount = 0;
        $feeItems = [];

        foreach ($request->fee_items as $item) {
            $fee = FeeStructure::findOrFail($item['fee_structure_id']);

            // Check if already paid
            $alreadyPaid = Payment::where('student_id', $student->id)
                ->where('fee_structure_id', $fee->id)
                ->where('quarter', $item['quarter'])
                ->where('status', 'completed')
                ->exists();

            if ($alreadyPaid) {
                return response()->json([
                    'error' => 'One or more selected fees are already paid.'
                ], 422);
            }

            $totalAmount += $fee->amount;
            $feeItems[] = [
                'fee_structure_id' => $fee->id,
                'quarter' => $item['quarter'],
                'amount' => $fee->amount,
            ];
        }

        // Create Razorpay order
        try {
            $order = $this->razorpay->order->create([
                'receipt' => 'rcpt_' . time(),
                'amount' => $totalAmount * 100, // Razorpay works in paise
                'currency' => 'INR',
                'notes' => [
                    'student_id' => $student->id,
                    'student_name' => $student->full_name,
                ],
            ]);

            // Create pending payment records
            foreach ($feeItems as $item) {
                Payment::create([
                    'student_id' => $student->id,
                    'fee_structure_id' => $item['fee_structure_id'],
                    'amount' => $item['amount'],
                    'payment_method' => 'razorpay',
                    'razorpay_order_id' => $order->id,
                    'status' => 'pending',
                    'quarter' => $item['quarter'],
                ]);
            }

            return response()->json([
                'order_id' => $order->id,
                'amount' => $totalAmount * 100,
                'currency' => 'INR',
                'key' => config('razorpay.key_id'),
                'student_name' => $student->full_name,
                'student_email' => auth()->user()->email,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create payment order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify the Razorpay payment signature and update records.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        try {
            // Verify signature
            $this->razorpay->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            // Update payment records
            $payments = Payment::where('razorpay_order_id', $request->razorpay_order_id)
                ->where('status', 'pending')
                ->get();

            foreach ($payments as $payment) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                    'payment_date' => now(),
                    'receipt_number' => Payment::generateReceiptNumber(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully!',
            ]);

        } catch (\Exception $e) {
            // Mark as failed
            Payment::where('razorpay_order_id', $request->razorpay_order_id)
                ->where('status', 'pending')
                ->update(['status' => 'failed']);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage(),
            ], 422);
        }
    }
}
