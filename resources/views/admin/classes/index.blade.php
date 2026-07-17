@extends('layouts.admin')

@section('title', 'Classes')
@section('page-title', 'Class Management')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Classes</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-plus mr-2 text-success"></i> Add New Class</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.classes.store') }}" method="POST">
                        @csrf
                        <div class="form-group"><label>Class Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Class 1" required>@error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
                        <div class="form-group"><label>Description</label><input type="text" name="description" class="form-control" placeholder="Optional description"></div>
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> Add Class</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-chalkboard mr-2 text-primary"></i> All Classes</h3></div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead style="background:#f8f9fa;"><tr><th>#</th><th>Class Name</th><th>Description</th><th>Students</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse($classes as $class)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $class->name }}</strong></td>
                                    <td>{{ $class->description ?? '-' }}</td>
                                    <td><span class="badge badge-info">{{ $class->students_count }}</span></td>
                                    <td>
                                        <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal{{ $class->id }}"><i class="fas fa-edit"></i></button>
                                        <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this class?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $class->id }}"><div class="modal-dialog"><div class="modal-content">
                                    <form action="{{ route('admin.classes.update', $class) }}" method="POST">@csrf @method('PUT')
                                        <div class="modal-header"><h5 class="modal-title">Edit Class</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                        <div class="modal-body">
                                            <div class="form-group"><label>Class Name</label><input type="text" name="name" class="form-control" value="{{ $class->name }}" required></div>
                                            <div class="form-group"><label>Description</label><input type="text" name="description" class="form-control" value="{{ $class->description }}"></div>
                                        </div>
                                        <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
                                    </form>
                                </div></div></div>
                            @empty
                                <tr><td colspan="5" class="text-center py-3 text-muted">No classes found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
