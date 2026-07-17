<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Receipt - {{ $payment->receipt_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; color: #333; padding: 30px; }

        .receipt-container { border: 2px solid #1a1c2e; border-radius: 10px; padding: 30px; max-width: 700px; margin: 0 auto; }

        .header { text-align: center; border-bottom: 2px solid #667eea; padding-bottom: 20px; margin-bottom: 20px; }
        .header .school-name { font-size: 24px; font-weight: bold; color: #1a1c2e; letter-spacing: 1px; }
        .header .school-address { font-size: 11px; color: #666; margin-top: 5px; }
        .header .receipt-title { font-size: 18px; font-weight: bold; color: #667eea; margin-top: 15px; text-transform: uppercase; letter-spacing: 2px; }

        .receipt-meta { display: table; width: 100%; margin-bottom: 20px; }
        .receipt-meta .left, .receipt-meta .right { display: table-cell; width: 50%; }
        .receipt-meta .right { text-align: right; }
        .receipt-meta p { margin-bottom: 4px; }
        .receipt-meta strong { color: #1a1c2e; }

        .student-info { background: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .student-info table { width: 100%; border-collapse: collapse; }
        .student-info td { padding: 5px 10px; }
        .student-info td:first-child { font-weight: bold; color: #555; width: 35%; }

        .fee-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .fee-table th { background: #1a1c2e; color: white; padding: 10px 15px; text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .fee-table td { padding: 10px 15px; border-bottom: 1px solid #eee; }
        .fee-table .total-row { background: #f0f4ff; font-weight: bold; font-size: 15px; }
        .fee-table .total-row td { border-top: 2px solid #667eea; }

        .payment-info { background: #e8f5e9; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .payment-info table { width: 100%; border-collapse: collapse; }
        .payment-info td { padding: 4px 10px; }
        .payment-info td:first-child { font-weight: bold; color: #2e7d32; width: 35%; }

        .footer { text-align: center; border-top: 1px solid #ddd; padding-top: 15px; }
        .footer p { font-size: 10px; color: #999; }
        .footer .stamp { color: #667eea; font-weight: bold; font-size: 12px; margin-bottom: 8px; }

        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); font-size: 80px; color: rgba(102, 126, 234, 0.05); font-weight: bold; letter-spacing: 5px; z-index: -1; }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="watermark">PAID</div>

        <div class="header">
            <div class="school-name">🏫 SCHOOL PORTAL</div>
            <div class="school-address">123 Education Street, Knowledge City | Phone: +91 98765 43210 | Email: info@school.com</div>
            <div class="receipt-title">Fee Receipt</div>
        </div>

        <div class="receipt-meta">
            <div class="left">
                <p><strong>Receipt No:</strong> {{ $payment->receipt_number }}</p>
                <p><strong>Date:</strong> {{ $payment->payment_date ? $payment->payment_date->format('d M Y, h:i A') : date('d M Y') }}</p>
            </div>
            <div class="right">
                <p><strong>Academic Year:</strong> {{ $payment->feeStructure->academicYear->name ?? 'N/A' }}</p>
                <p><strong>Payment Mode:</strong> Online (Razorpay)</p>
            </div>
        </div>

        <div class="student-info">
            <table>
                <tr>
                    <td>Student Name</td>
                    <td>{{ $student->full_name }}</td>
                    <td>Admission No</td>
                    <td>{{ $student->admission_no }}</td>
                </tr>
                <tr>
                    <td>Class</td>
                    <td>{{ $student->academicClass->name ?? '-' }} {{ $student->section ? '- ' . $student->section : '' }}</td>
                    <td>Roll No</td>
                    <td>{{ $student->roll_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Parent/Guardian</td>
                    <td>{{ $student->parent_name }}</td>
                    <td>Phone</td>
                    <td>{{ $student->parent_phone ?? $student->phone ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <table class="fee-table">
            <thead>
                <tr>
                    <th>Fee Category</th>
                    <th>Period</th>
                    <th>Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->feeStructure->feeCategory->name ?? 'N/A' }}</td>
                    <td>{{ $payment->quarter }}</td>
                    <td>₹{{ number_format($payment->amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">Total Paid:</td>
                    <td>₹{{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="payment-info">
            <table>
                <tr>
                    <td>Transaction ID</td>
                    <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Razorpay Order ID</td>
                    <td>{{ $payment->razorpay_order_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Payment Status</td>
                    <td style="color: #2e7d32; font-weight: bold;">✅ COMPLETED</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p class="stamp">✦ Computer Generated Receipt — No Signature Required ✦</p>
            <p>Thank you for your payment. For any queries, please contact the school office.</p>
            <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
        </div>
    </div>
</body>
</html>
