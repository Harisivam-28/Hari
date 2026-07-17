@extends('layouts.admin')

@section('title', 'Fee Structures')
@section('page-title', 'Fee Structures')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Fee Structures</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title" style="font-weight:600;"><i class="fas fa-sitemap mr-2 text-primary"></i> Fee Structures</h3>
            <a href="{{ route('admin.fee-structures.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Add Fee Structure</a>
        </div>
        <div class="card-body">
            <table id="feeTable" class="table table-hover table-striped" style="width:100%">
                <thead>
                    <tr><th>Year</th><th>Class</th><th>Fee Category</th><th>Amount</th><th>Frequency</th><th>Due Date</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($feeStructures as $fee)
                        <tr>
                            <td>{{ $fee->academicYear->name }}</td>
                            <td><strong>{{ $fee->academicClass->name }}</strong></td>
                            <td><span class="badge badge-info">{{ $fee->feeCategory->name }}</span></td>
                            <td><strong>₹{{ number_format($fee->amount, 2) }}</strong></td>
                            <td><span class="badge badge-{{ $fee->frequency === 'quarterly' ? 'warning' : 'success' }}">{{ ucfirst($fee->frequency) }}</span></td>
                            <td>{{ $fee->due_date ? $fee->due_date->format('d M Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.fee-structures.edit', $fee) }}" class="btn btn-warning btn-xs"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.fee-structures.destroy', $fee) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>$(function(){ $('#feeTable').DataTable({ responsive: true }); });</script>
@endpush
