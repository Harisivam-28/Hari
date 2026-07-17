@extends('layouts.student')

@section('title', 'Fee Receipts')
@section('page-title', 'Fee Receipts')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Receipts</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-weight:600;"><i class="fas fa-file-invoice mr-2 text-primary"></i> Payment Receipts</h3>
        </div>
        <div class="card-body">
            <table id="receiptsTable" class="table table-hover table-striped" style="width:100%">
                <thead>
                    <tr><th>Receipt #</th><th>Fee Category</th><th>Quarter</th><th>Amount</th><th>Date</th><th>Transaction ID</th><th>Download</th></tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td><strong>{{ $payment->receipt_number }}</strong></td>
                            <td>{{ $payment->feeStructure->feeCategory->name ?? 'N/A' }}</td>
                            <td>{{ $payment->quarter }}</td>
                            <td><strong>₹{{ number_format($payment->amount, 2) }}</strong></td>
                            <td>{{ $payment->payment_date->format('d M Y') }}</td>
                            <td><small class="text-muted">{{ $payment->transaction_id }}</small></td>
                            <td>
                                <a href="{{ route('student.receipts.download', $payment) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-download mr-1"></i> PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No receipts available yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>$(function(){ $('#receiptsTable').DataTable({ responsive: true, order: [[4, 'desc']] }); });</script>
@endpush
