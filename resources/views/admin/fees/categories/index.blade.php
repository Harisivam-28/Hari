@extends('layouts.admin')

@section('title', 'Fee Categories')
@section('page-title', 'Fee Categories')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Fee Categories</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-plus mr-2 text-success"></i> Add Fee Category</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.fee-categories.store') }}" method="POST">
                        @csrf
                        <div class="form-group"><label>Category Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Tuition Fee" required>@error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
                        <div class="form-group"><label>Description</label><input type="text" name="description" class="form-control" placeholder="Optional"></div>
                        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> Add Category</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title" style="font-weight:600;"><i class="fas fa-tags mr-2 text-primary"></i> All Fee Categories</h3></div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead style="background:#f8f9fa;"><tr><th>#</th><th>Name</th><th>Description</th><th>Fee Structures</th><th>Actions</th></tr></thead>
                        <tbody>
                            @forelse($categories as $cat)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $cat->name }}</strong></td>
                                    <td>{{ $cat->description ?? '-' }}</td>
                                    <td><span class="badge badge-info">{{ $cat->fee_structures_count }}</span></td>
                                    <td>
                                        <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editCatModal{{ $cat->id }}"><i class="fas fa-edit"></i></button>
                                        <form action="{{ route('admin.fee-categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <div class="modal fade" id="editCatModal{{ $cat->id }}"><div class="modal-dialog"><div class="modal-content">
                                    <form action="{{ route('admin.fee-categories.update', $cat) }}" method="POST">@csrf @method('PUT')
                                        <div class="modal-header"><h5 class="modal-title">Edit Category</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                        <div class="modal-body">
                                            <div class="form-group"><label>Name</label><input type="text" name="name" class="form-control" value="{{ $cat->name }}" required></div>
                                            <div class="form-group"><label>Description</label><input type="text" name="description" class="form-control" value="{{ $cat->description }}"></div>
                                        </div>
                                        <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
                                    </form>
                                </div></div></div>
                            @empty
                                <tr><td colspan="5" class="text-center py-3 text-muted">No fee categories found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
