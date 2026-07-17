<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'fee_structure_id',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_id',
        'razorpay_order_id',
        'razorpay_signature',
        'status',
        'receipt_number',
        'quarter',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class);
    }

    /**
     * Generate a unique receipt number.
     */
    public static function generateReceiptNumber(): string
    {
        $year = date('Y');
        $lastReceipt = static::where('receipt_number', 'like', "RCP-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastReceipt) {
            $lastNumber = (int) substr($lastReceipt->receipt_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "RCP-{$year}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
