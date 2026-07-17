@extends('layouts.admin')

@section('title', 'Academic Years')
@section('page-title', 'Academic Year Management')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Academic Years</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-plus mr-2 text-success"></i> Add Academic Year</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.academic-years.store') }}" method="POST">
                        @csrf
                        <div class="form-group"><label>Year Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. 2026-2027" required>@error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label>Start Date <span class="text-danger">*</span></label><input type="date" name="start_date" class="form-control" required></div></div>
                            <div class="col-md-6"><div class="form-group"><label>End Date <span class="text-danger">*</span></label><input type="date" name="end_date" class="form-control" required></div></div>
                        </div>
                        <div class="form-group"><div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="is_current" name="is_current" value="1"><label class="custom-control-label" for="is_current">Set as Current Year</label></div></div>
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> Add Year</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-calendar-alt mr-2 text-primary"></i> All Academic Years</h3></div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead style="background:#f8f9fa;"><tr><th>Year</th><th>Start</th><th>End</th><th>Status</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse($academicYears as $year)
                                <tr>
                                    <td><strong>{{ $year->name }}</strong></td>
                                    <td>{{ $year->start_date->format('d M Y') }}</td>
                                    <td>{{ $year->end_date->format('d M Y') }}</td>
                                    <td>
                                        @if($year->is_current)
                                            <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Current</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.academic-years.destroy', $year) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this year?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-3 text-muted">No academic years found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
