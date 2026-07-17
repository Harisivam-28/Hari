@extends('layouts.admin')

@section('title', 'Payments')
@section('page-title', 'Payment Reports')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Payments</li>
@endsection

@section('content')
    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="small-box" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="inner text-white"><h3>₹{{ number_format($totalCompleted, 2) }}</h3><p>Total Completed</p></div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="small-box" style="background: linear-gradient(135deg, #fc5c7d 0%, #6a82fb 100%);">
                <div class="inner text-white"><h3>₹{{ number_format($totalPending, 2) }}</h3><p>Total Pending</p></div>
                <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card">
        <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-filter mr-2 text-info"></i> Filters</h3></div>
        <div class="card-body">
            <form method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <div class="form-group mb-0"><label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">All</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3"><div class="form-group mb-0"><label>From Date</label><input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}"></div></div>
                <div class="col-md-3"><div class="form-group mb-0"><label>To Date</label><input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}"></div></div>
                <div class="col-md-3"><button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Filter</button></div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-receipt mr-2 text-primary"></i> All Payments</h3></div>
        <div class="card-body">
            <table id="paymentsTable" class="table table-hover table-striped" style="width:100%">
                <thead><tr><th>Receipt #</th><th>Student</th><th>Fee Category</th><th>Quarter</th><th>Amount</th><th>Date</th><th>Transaction ID</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td><strong>{{ $payment->receipt_number ?? '-' }}</strong></td>
                            <td>{{ $payment->student->full_name ?? 'N/A' }}<br><small class="text-muted">{{ $payment->student->admission_no ?? '' }}</small></td>
                            <td>{{ $payment->feeStructure->feeCategory->name ?? 'N/A' }}</td>
                            <td>{{ $payment->quarter }}</td>
                            <td><strong>₹{{ number_format($payment->amount, 2) }}</strong></td>
                            <td>{{ $payment->payment_date ? $payment->payment_date->format('d M Y h:i A') : '-' }}</td>
                            <td><small>{{ $payment->transaction_id ?? '-' }}</small></td>
                            <td><span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($payment->status) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>$(function(){ $('#paymentsTable').DataTable({ responsive: true, order: [[5, 'desc']] }); });</script>
@endpush
