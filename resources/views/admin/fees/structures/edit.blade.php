@extends('layouts.admin')

@section('title', 'Edit Fee Structure')
@section('page-title', 'Edit Fee Structure')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.fee-structures.index') }}">Fee Structures</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-edit mr-2 text-warning"></i> Edit Fee Structure</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.fee-structures.update', $feeStructure) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label>Academic Year <span class="text-danger">*</span></label>
                            <select name="academic_year_id" class="form-control" required>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}" {{ $feeStructure->academic_year_id == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Class <span class="text-danger">*</span></label>
                            <select name="class_id" class="form-control" required>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ $feeStructure->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Fee Category <span class="text-danger">*</span></label>
                            <select name="fee_category_id" class="form-control" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $feeStructure->fee_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" value="{{ $feeStructure->amount }}" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>Frequency <span class="text-danger">*</span></label>
                            <select name="frequency" class="form-control" required>
                                <option value="quarterly" {{ $feeStructure->frequency == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="annual" {{ $feeStructure->frequency == 'annual' ? 'selected' : '' }}>Annual</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="{{ $feeStructure->due_date?->format('Y-m-d') }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fas fa-save mr-2"></i> Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
