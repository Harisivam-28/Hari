@extends('layouts.student')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <!-- Welcome Card -->
    <div class="card" style="background: linear-gradient(135deg, #0f3460 0%, #16213e 100%); color: white;">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:70px;height:70px;background:rgba(255,255,255,0.15);overflow:hidden;">
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" style="width:70px;height:70px;object-fit:cover;">
                        @else
                            <span style="font-size:1.5rem;font-weight:700;">{{ strtoupper(substr($student->first_name,0,1).substr($student->last_name,0,1)) }}</span>
                        @endif
                    </div>
                </div>
                <div class="col">
                    <h3 class="mb-1" style="font-weight:700;">Welcome back, {{ $student->first_name }}! 👋</h3>
                    <p class="mb-0" style="opacity:0.8;">
                        {{ $student->academicClass->name ?? '' }} {{ $student->section ? '- Section ' . $student->section : '' }} | Roll No: {{ $student->roll_number ?? '-' }} | Adm No: {{ $student->admission_no }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Summary Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="inner text-white"><h3>₹{{ number_format($totalFees, 0) }}</h3><p>Total Fees</p></div>
                <div class="icon"><i class="fas fa-rupee-sign"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="inner text-white"><h3>₹{{ number_format($totalPaid, 0) }}</h3><p>Amount Paid</p></div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #fc5c7d 0%, #6a82fb 100%);">
                <div class="inner text-white"><h3>₹{{ number_format($pendingAmount, 0) }}</h3><p>Pending Amount</p></div>
                <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="inner text-white"><h3>{{ $nextDueDate ? $nextDueDate->format('d M') : 'N/A' }}</h3><p>Next Due Date</p></div>
                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Payments -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-bolt mr-2 text-warning"></i> Quick Actions</h3></div>
                <div class="card-body">
                    <a href="{{ route('student.fees.index') }}" class="btn btn-info btn-block mb-2"><i class="fas fa-money-bill-wave mr-2"></i> Pay Fees Online</a>
                    <a href="{{ route('student.receipts.index') }}" class="btn btn-outline-primary btn-block mb-2"><i class="fas fa-file-invoice mr-2"></i> Download Receipts</a>
                    <a href="{{ route('student.profile') }}" class="btn btn-outline-secondary btn-block"><i class="fas fa-id-card mr-2"></i> View Profile</a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-history mr-2 text-success"></i> Recent Payments</h3></div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead style="background:#f8f9fa;"><tr><th>Fee Type</th><th>Quarter</th><th>Amount</th><th>Date</th><th>Receipt</th></tr></thead>
                        <tbody>
                            @forelse($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->feeStructure->feeCategory->name ?? 'N/A' }}</td>
                                    <td>{{ $payment->quarter }}</td>
                                    <td><strong>₹{{ number_format($payment->amount, 2) }}</strong></td>
                                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('student.receipts.download', $payment) }}" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-3 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No payments yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
