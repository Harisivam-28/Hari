@extends('layouts.admin')

@section('title', 'Student Profile')
@section('page-title', 'Student Profile')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a></li>
    <li class="breadcrumb-item active">{{ $student->full_name }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center mb-3" style="width:120px;height:120px;background:linear-gradient(135deg,#667eea,#764ba2);overflow:hidden;">
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" style="width:120px;height:120px;object-fit:cover;">
                        @else
                            <span class="text-white" style="font-size:2.5rem;font-weight:700;">{{ strtoupper(substr($student->first_name,0,1).substr($student->last_name,0,1)) }}</span>
                        @endif
                    </div>
                    <h4 class="mb-1" style="font-weight:700;">{{ $student->full_name }}</h4>
                    <p class="text-muted mb-2">{{ $student->admission_no }}</p>
                    <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'secondary' }} px-3 py-2">{{ ucfirst($student->status) }}</span>
                    <hr>
                    <div class="text-left">
                        <p><i class="fas fa-chalkboard-teacher mr-2 text-primary"></i> <strong>Class:</strong> {{ $student->academicClass->name ?? '-' }} - {{ $student->section ?? '' }}</p>
                        <p><i class="fas fa-id-badge mr-2 text-info"></i> <strong>Roll No:</strong> {{ $student->roll_number ?? '-' }}</p>
                        <p><i class="fas fa-envelope mr-2 text-warning"></i> <strong>Email:</strong> {{ $student->user->email }}</p>
                        <p><i class="fas fa-phone mr-2 text-success"></i> <strong>Phone:</strong> {{ $student->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-info-circle mr-2 text-primary"></i> Personal Details</h3></div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tr><th width="35%">Date of Birth</th><td>{{ $student->date_of_birth ? $student->date_of_birth->format('d M Y') : '-' }}</td></tr>
                        <tr><th>Gender</th><td>{{ ucfirst($student->gender) }}</td></tr>
                        <tr><th>Blood Group</th><td>{{ $student->blood_group ?? '-' }}</td></tr>
                        <tr><th>Aadhar Number</th><td>{{ $student->aadhar_number ?? '-' }}</td></tr>
                        <tr><th>Address</th><td>{{ $student->address ?? '-' }}</td></tr>
                        <tr><th>Admission Date</th><td>{{ $student->admission_date ? $student->admission_date->format('d M Y') : '-' }}</td></tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-users mr-2 text-success"></i> Parent / Guardian</h3></div>
                <div class="card-body">
                    <table class="table table-bordered mb-0">
                        <tr><th width="35%">Parent Name</th><td>{{ $student->parent_name }}</td></tr>
                        <tr><th>Parent Phone</th><td>{{ $student->parent_phone ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-receipt mr-2 text-info"></i> Payment History</h3></div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead style="background:#f8f9fa;"><tr><th>Fee Category</th><th>Quarter</th><th>Amount</th><th>Date</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($student->payments as $payment)
                                <tr>
                                    <td>{{ $payment->feeStructure->feeCategory->name ?? 'N/A' }}</td>
                                    <td>{{ $payment->quarter }}</td>
                                    <td>₹{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_date ? $payment->payment_date->format('d M Y') : '-' }}</td>
                                    <td><span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($payment->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-3 text-muted">No payment history.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
